<?php

class Client
{
    private $msisdn, $superPassword, $token, $subId;

    private $api_url = 'https://api.life.com.ua/mobile/';

    private $RESPONSE_CODE = array(
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
    '-11' => 'Number doesn\'t exists',
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
);

    public function __construct($msisdn, $superPassword)
    {
        $this->msisdn = $msisdn;
        $this->superPassword = $superPassword;

        $data = $this->signIn();

        $this->token = $data->token;
        $this->subId = $data->subId;

    }

    public function request($method, $params)
    {
        /* merging knows data with dynamic */
        $params = array_merge(["accessKeyCode" => "7"], $params);


        $built_query = urldecode(http_build_query($params));
        $params_in_url = $method."?".$built_query."&signature=";

        // generation unique signature for every request
        $signed_url = hash_hmac("sha1", $params_in_url, "E6j_$4UnR_)0b", true);
        $signature = base64_encode($signed_url);


        $url = ($this->api_url . $params_in_url . urlencode($signature));
       //echo "<pre>" . $url . "</pre>";

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 25);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_data =  simplexml_load_string($response);
        $code = json_decode($decoded_data->responseCode);

        if (!($code)) {
            return $decoded_data;
        } else {
           return (['Error' => $this->RESPONSE_CODE[$code]]);
        }
    }

    private function signIn(){
        $data = $this->request("signIn",["msisdn" => $this->msisdn, "superPassword" => $this->superPassword]);
        if($data["Error"] == NULL) {
            return $data;
        }
        return json_encode($data);
    }

    public function getToken() {
        return $this->token;
    }

    public function getSubId() {
        return $this->subId;
    }

    public function getSummaryData() {
        $data = $this->request("getSummaryData", ["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$this->token"]);
        return $data;
    }


    public function getBalances() {
        $data = $this->request("getBalances",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$this->token"]);
        return $data;
    }

    public function callMeBack($msisdnB) {
        $data = $this->request("getBalances",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "msisdnB" => "$msisdnB", "token" => "$this->token"]);
        return $data;
    }

    public function requestBalanceTransfer($msisdnB) {
        $data = $this->request("requestBalanceTransfer",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "msisdnB" => "$msisdnB", "token" => "$this->token"]);
        return $data;
    }

    public function changeLanguage($newLanguageId) {
        $data = $this->request("changeLanguage",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "newLanguageId" => "$newLanguageId", "token" => "$this->token"]);
        return $data;
    }


    public function changeSuperPassword($old_password, $new_password) {
        $data = $this->request("changeSuperPassword",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "oldPassword" => "$old_password", "newPassword" => "$new_password", "token" => "$this->token"]);
        return $data;
    }

    public function getAvailableTariffs(){
        $data = $this->request("getAvailableTariffs",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$this->token"]);
        return $data;
    }

    public function getExpensesSummary($period){
        $data = $this->request("getExpensesSummary",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "monthPeriod" => "$period", "token" => "$this->token"]);
        return $data;
    }

    public function getLanguages() {
        $data = $this->request("getLanguages",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$this->token"]);
        return $data;
    }

    public function getPaymentsHistory($period) {
        $data = $this->request("getPaymentsHistory",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "monthPeriod" => "$period", "token" => "$this->token"]);
        return $data;
    }

    public function getServices() {
        $data = $this->request("getServices",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "token" => "$this->token"]);
        return $data;
    }


    public function getUIProperties($last_date_update){
        $data = $this->request("getUIProperties",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "lastDateUpdate" => "$last_date_update" ,"token" => "$this->token"]);
        return $data;
    }

    public function offerAction($offerCode){
        $data = $this->request("getUIProperties",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "offerCode" => "$offerCode" ,"token" => "$this->token"]);
        return $data;
    }

    public function refillBalanceByScratchCard($secretCode){
        $data = $this->request("refillBalanceByScratchCard",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "secretCode" => "$secretCode" ,"token" => "$this->token"]);
        return $data;
    }

    public function removeFromPreProcessing($serviceCode){
        $data = $this->request("refillBalanceByScratchCard",["msisdn" => $this->msisdn, "languageId" => "ru", "osType" => "ANDROID", "serviceCode" => "$serviceCode" ,"token" => "$this->token"]);
        return $data;
    }

    public function signOut(){
        $data = $this->request("getServices",["msisdn" => $this->msisdn, "subId" => "$subId"]);
    }

}

$client = new Client('380930000000', '123456');

header('Content-type: Application/json');

// ##EXAMPLE
echo json_encode($client->getBalances());


