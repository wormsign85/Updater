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
    )
);

$config_db = array(
    'username' => 'root',
    'password' => '',
    'connection' => 'mysql:host=localhost;dbname=wormsignh_haffner',
    'dbtokshop' => 'tokshop'
);

$config_cel = array(
    'username' => 'root',
    'password' => '',
    'connection' => 'mysql:host=localhost;dbname=wormsignh_cellect',
    'dbtokshop' => 'products'
);

$config_ws = array(
    'username' => 'root',
    'password' => '',
    'connection' => 'mysql:host=localhost;dbname=wormsignh_wormsign_hu',
    'dbtokshop' => 'products'
);
/*
  $config_db_test = array (
  'username' =>'wormsignh_worm',
  'password' =>'IxOn1985',
  'connection' =>'mysql:host=localhost;dbname=wormsignh_wormtest',
  );

  $config_db_update = array (
  'username' =>'wormsignh_update',
  'password' =>'IxOn1985',
  'connection' =>'mysql:host=localhost;dbname=wormsignh_update',
  );

  $config_db_wormsign = array (
  'username' =>'wormsignh_wormsign_hu',
  'password' =>'IxOn1985',
  'connection' =>'mysql:host=localhost;dbname=wormsignh_wormsignh',
  ); */

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

require_once 'lib/db.php';
