<?php

$config = array(
    'akkucentral' => array(
        'Username' => 'akkumulator',
        'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
        'ShopId' => '7171',
        'AuthCode' => '2b361baa70',
    ),
    'akkutkeresek' => array(
        'Username' => 'akkumulator',
        'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
        'ShopId' => '20588',
        'AuthCode' => '7da1c1715c',
    ),
    'realpower' => array(
        'Username' => 'akkumulator',
        'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
        'ShopId' => '41731',
        'AuthCode' => 'd38efd0c5c',
    )
);

$config_user = array(
    'username' => 'wormsignh_worm',
    'password' => 'IxOn1985'
);

$config_db = array(
    'username' => $config_user['username'],
    'password' => $config_user['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_mydb',
);


/*
  $config_user = array(
  'username' => 'root',
  'password' => ''
  );
 */


$config_db_ws = array(
    'username' => $config_user['username'],
    'password' => $config_user['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_wormsign_hu',
);

$config_db = array(
    'username' => $config_user['username'],
    'password' => $config_user['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_mydb',
);

$config_db_test = array(
    'username' => $config_user['username'],
    'password' => $config_user['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_wormtest',
);

$config_db_update = array(
    'username' => $config_user['username'],
    'password' => $config_user['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_update',
);

$config_db_wormsign = array(
    'username' => $config_user['username'],
    'password' => $config_user['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_wormsignh',
);

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
