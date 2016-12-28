<?php

set_time_limit(1200);

// init
$soap_server = "https://api.unas.eu/shop/?wsdl";
ini_set("soap.wsdl_cache_enabled", "0");
///////////////////////////////////////////////
// auth
//$auth = array(
//    'Username' => 'akkumulator',
//    'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
//    'ShopId' => '7171',
//    'AuthCode' => '2b361baa70'
//);

$auth = array(
    'Username' => 'akkumulator',
    'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
    'ShopId' => '93443',
    'AuthCode' => '5e5dc403f6'
);
///////////////////////////////////////////////
// connect
$client = new SoapClient($soap_server);
///////////////////////////////////////////////
//getOrder
try {
    $params = array(
        'Url' => 'http://www.wormsign.hu/tokotveszek.csv',
        'DelType' => 'product'
    );
    $response = $client->setProductDB($auth, $params);
    echo "<strong>setProductdb Response:</strong><br /> ";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
} catch (SoapFault $error) {
    echo "<strong>setProductdb Error:</strong><br /> ";
    echo "<pre>" . print_r($error, true) . "</pre>";
}

