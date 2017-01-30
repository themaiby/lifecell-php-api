<?php
// api 
include('./httpful.phar');

/*
# The list of available API method names:
# (16/22)
#
# [-] activateDeactivateService
# [+] callMeBack
# [+] changeLanguage
# [+] changeSuperPassword
# [-] changeTariff
# [+] getAvailableTariffs
# [+] getBalances
# [+] getExpensesSummary
# [+] getLanguages
# [+] getPaymentsHistory
# [-] getSeparateBalances
# [+] getServices
# [+] getSummaryData
# [+] getToken
# [+] getUIProperties
# [-] offerAction
# [+] refillBalanceByScratchCard
# [-] removeFromPreProcessing
# [+] requestBalanceTransfer
# [+] signIn
# [+] signOut
# [-] transferBalance
*/

$debug = 1;
$RESPONSE_CODE = [
    '0' => 'Успешная авторизация',
    '-1' => 'Время ожидания истекло',
    '-2' => 'Внутренняя ошибка',
    '-3' => 'Неправильный список параметров',
    '-4' => 'Авторизация не удалась',
    '-5' => 'Код доступа истёк',
    '-6' => 'Авторизация не удалась',
    '-7' => 'СуперПароль неверный',
    '-8' => 'Неверный ID абонента',
    '-9' => 'Только для абонентов предоплаты',
    '-10' => 'СуперПароль заблокирован. Закажите новый.',
    '-11' => 'Номер не существует.',
    '-12' => 'Токен истёк.',
    '-13' => 'Ошибка смены тарифного плана.',
    '-14' => 'Ошибка активации услуги.',
    '-15' => 'Ошибка активации акции.',
    '-16' => 'Произошла ошибка при получении тарифов.',
    '-17' => 'Произошла ошибка при получении услуг.',
    '-18' => 'REMOVE_SERVICE_FROM_PREPROCESSING_FAILED',
    '-19' => 'Логика заблокирована (чё???)',
    '-20' => 'Слишком много запросов. Пажжи.',
    '-40' => 'PAYMENTS_OR_EXPENSES_MISSED',
    '-21474833648' => 'Внутренняя ошибка приложения',
];


if ($debug) {}




function request($method, $params){ // создаёт запрос, принимает метод и параметры запроса массивом (номер, токен и тп)

$params = array_merge(["accessKeyCode" => "7"], $params); // объединение известных данных с полученными

$built_query = urldecode(http_build_query($params));
$params_in_url = $method."?".$built_query."&signature=";
$signed_url = hash_hmac(sha1, $params_in_url, "E6j_$4UnR_)0b", true);
$signature = base64_encode($signed_url);
$url = "https://api.life.com.ua/mobile/" . $api_url . $params_in_url . $signature;
  
$response = \Httpful\Request::get($url)
    ->expectsXml()
    ->send();


echo "DEBUG: valid url: ".$url."<br><br>";

return $response;
}



function signIn($msisdn, $superPassword){ // функция авторизации. В дальнейшем будет использоваться автоматически в каждом запросе
	global $RESPONSE_CODE;


    $data = request("signIn",["msisdn" => $msisdn, "superPassword" => $superPassword]);
    return $data;
}



// методы -------------------------------------------------

function getSummaryData() {  // основная информация


$data = request("getSummaryData",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$token"]);   
if (($data->body->responseCode)==("-12")) {cleanCookie();}
return $data;
}

function getBalances() { // балансы
$data = request("getBalances",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$token"]);   
return $data;
}

function callMeBack($msisdnB) { // запрос о перезвоне
$data = request("getBalances",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "msisdnB" => "$msisdnB", "token" => "$token"]);   
return $data;
}

function requestBalanceTransfer($msisdnB) { // запрос о переводе баланса
$data = request("requestBalanceTransfer",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "msisdnB" => "$msisdnB", "token" => "$token"]);
return $data;
}

function changeLanguage($newLanguageId) { // смена языка
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

function getToken() { // получение токена без участия суперпароля. Пока применения не нашёл, но можно использовать, сохраняя СП в БД. Не очень очется :)
$data = request("getToken",["msisdn" => $msisdn, "subId" => "$subId"]);   
return $data;
}

function getUIProperties($last_date_update){ // яя хз зачем это
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

function removeFromPreProcessing($serviceCode){ // походу, отключить услугу из ожидания оплаты
$data = request("refillBalanceByScratchCard",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "serviceCode" => "$serviceCode" ,"token" => "$token"]);   
return $data;
}

/*
function getSeparateBalances(){  // not working


$data = request("getSeparateBalances",["msisdn" => $msisdn, "languageId" => "ru", "osType" => "ANDROID", "balanceCode" => "Line_Main", "token" => "$token"]);   

return $data;
}
*/


function signOut(){
$data = request("getServices",["msisdn" => $msisdn, "subId" => "$subId"]);   
}


?>