<?php

$rootDir = realpath(dirname((__FILE__))) . '/';

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
    'tokotveszek' => array(
        'Username' => 'akkumulator',
        'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
        'ShopId' => '93443',
        'AuthCode' => '5e5dc403f6',
    )
);

$sql_con = array(
    'username' => 'wormsignh_worm',
    'password' => 'IxOn1985'
);

$config_db = array(
    'username' => 'wormsignh_worm',
    'password' => 'IxOn1985',
    'connection' => 'mysql:host=localhost;dbname=wormsignh_haffner',
);



  $config_db_update = array (
  'username' =>'wormsignh_worm',
  'password' =>'IxOn1985',
  'connection' =>'mysql:host=localhost;dbname=wormsignh_update',
  );

  $config_db_wormsign = array (
  'username' =>'wormsignh_worm',
  'password' =>'IxOn1985',
  'connection' =>'mysql:host=localhost;dbname=wormsignh_wormsign_hu',
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

require_once dirname(__FILE__) . '/db.php';
