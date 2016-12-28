<?php

//echo getcwd();
$config = array(
    'system' => array(
        'axapta_xml_dir' => '../xml',
        'log_dir' => '../log'
    ),
    'akkucentral' => array(
        'Username' => 'akkumulator',
        'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
        'ShopId' => '7171',
        'AuthCode' => '2b361baa70',
    ),
    'unas_soap' => array(
        'akku' => array(
            'Username' => 'akkumulator',
            'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
            'ShopId' => '7171',
            'AuthCode' => '2b361baa70',
        ))
);



$config_user = array(
    'username' => 'wormsignh_worm',
    'password' => 'IxOn1985'
);


$config_db_my = array(
    'username' => $config_user['username'],
    'password' => $config_user['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_mydb',
);

$config_db_stock = array(
    'username' => $config_user['username'],
    'password' => $config_user['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_update',
);

//PDO kapcsolódás
$rootDir = realpath(dirname((__FILE__))) . '/';

function pdo_connection($config_db_stock) {
    try {
        $conn = new PDO($config_db_stock['connection'], $config_db_stock['username'], $config_db_stock['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    return $conn;
}

$pdo_conn = pdo_connection($config_db_my);

//log error mentése

function log_unas($filename, $message) {
    file_put_contents($filename, date('Y-m-d H:i:s') . ' ' . $message . "\n", FILE_APPEND);
}

function response_unas($filename, $message) {
    file_put_contents($filename, date('Y-m-d H:i:s') . ' ' . $message . "\n", FILE_APPEND);
}

//Unas API kapcsolat
function get_unas_client() {
    $soap_server = "https://api.unas.eu/shop/?wsdl";
    ini_set("soap.wsdl_cache_enabled", "0");
///////////////////////////////////////////////
// auth
///////////////////////////////////////////////
// connect
    $client = new SoapClient($soap_server);
    return $client;
}

$client = get_unas_client();


require_once dirname(__FILE__) . '/db.php';
