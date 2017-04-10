<?php
include('./httpful.phar');
$RESPONSE_CODE = [
    '0' => 'Success',
    '-1' => 'Session timeout',
    '-2' => 'Internal Error',
    '-3' => 'Invalid parameter list',
    '-4' => 'Authorization failed',
    '-5' => 'Token expired',
    '-6' => 'Autorization failed (wrong link)',
    '-7' => 'Wrong Superpassword',
    '-8' => 'Wrong number',
    '-9' => 'Only for prepaid customers',
    '-10' => 'Superpassword locked. Order new superpassword',
    '-11' => 'Number doesnt exists',
    '-12' => 'Session expired',
    '-13' => 'Tariff plan changing error.',
    '-14' => 'Service activating error',
    '-15' => 'Order activation error',
    '-16' => 'Failed to get the list of tariffs',
    '-17' => 'Failed to get the list of services',
    '-18' => 'Remove service from preprocessing failed',
    '-19' => 'Logic is blocked',
    '-20' => 'Too many requests',
    '-40' => 'Payments of expenses missed',
    '-21474833648' => 'Internal application error',
];

	// генерирует готовый запрос и возвращает ответ от api.lifecell.com.ua/mobile/{methodName}
function request($method, $params){ 

	// объединение известных данных с полученными
$params = array_merge(["accessKeyCode" => "7"], $params); 

$built_query = urldecode(http_build_query($params));
$params_in_url = $method."?".$built_query."&signature=";
	
	// уникальная подпись каждого запроса
$signed_url = hash_hmac(sha1, $params_in_url, "E6j_$4UnR_)0b", true);
$signature = base64_encode($signed_url);
	
$url = urlencode("https://api.life.com.ua/mobile/" . $api_url . $params_in_url . $signature);
  
$response = \Httpful\Request::get($url)
    ->expectsXml()
    ->send();

	// Раскоментировать для вывода текущего api-URL
// echo "Valid url: ".$url."<br><br>";

return $response;
}


function signIn($msisdn, $superPassword){ 
	global $RESPONSE_CODE;
    	$data = request("signIn",["msisdn" => $msisdn, "superPassword" => $superPassword]);
    return $data;
}

// methods

function getSummaryData() {
$data = request("getSummaryData",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$token"]);   
if (($data->body->responseCode)==("-12")) {cleanCookie();}
return $data;
}

function getBalances() {
$data = request("getBalances",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$token"]);   
return $data;
}

function callMeBack($msisdnB) {
$data = request("getBalances",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "msisdnB" => "$msisdnB", "token" => "$token"]);   
return $data;
}

function requestBalanceTransfer($msisdnB) {
$data = request("requestBalanceTransfer",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "msisdnB" => "$msisdnB", "token" => "$token"]);
return $data;
}

function changeLanguage($newLanguageId) {
$data = request("changeLanguage",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "newLanguageId" => "$newLanguageId", "token" => "$token"]);   
return $data;
}


function changeSuperPassword($old_password, $new_password) {
$data = request("changeSuperPassword",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "oldPassword" => "$old_password", "newPassword" => "$new_password", "token" => "$token"]);   
return $data;
}

function getAvailableTariffs(){
$data = request("getAvailableTariffs",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$token"]);   
return $data;
}

function getExpensesSummary($period){
$data = request("getExpensesSummary",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "monthPeriod" => "$period", "token" => "$token"]);   
return $data;
}

function getLanguages() {
$data = request("getLanguages",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$token"]);   
return $data;
}

function getPaymentsHistory($period) {
$data = request("getPaymentsHistory",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "monthPeriod" => "$period", "token" => "$token"]);   
return $data;
}

function getServices() {
$data = request("getServices",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$token"]);   
return $data;
}

function getToken() {
$data = request("getToken",["msisdn" => $msisdn, "subId" => "$subId"]);   
return $data;
}

function getUIProperties($last_date_update){
$data = request("getUIProperties",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "lastDateUpdate" => "$last_date_update" ,"token" => "$token"]);
return $data;
}

function offerAction($offerCode){
$data = request("getUIProperties",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "offerCode" => "$offerCode" ,"token" => "$token"]);   
return $data;
}

function refillBalanceByScratchCard($secretCode){
$data = request("refillBalanceByScratchCard",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "secretCode" => "$secretCode" ,"token" => "$token"]);   
return $data;
}

function removeFromPreProcessing($serviceCode){
$data = request("refillBalanceByScratchCard",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "serviceCode" => "$serviceCode" ,"token" => "$token"]);   
return $data;
}

function signOut(){
$data = request("getServices",["msisdn" => $msisdn, "subId" => "$subId"]);   
}
?>
