<?php

$rootDir = realpath(dirname((__FILE__))) . '/';

$config = array(
    'system' => array(
        'axapta_xml_dir' => $rootDir,
        'log_dir' => $rootDir . '/log/',
    ),
    'unas_soap' => array(
        'akkucentral' => array(
            'Username' => 'akkumulator',
            'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
            'ShopId' => '7171',
            'AuthCode' => '2b361baa70',
        )/* ,
      'mitsu' => array(
      'Username' => 'Wormsign',
      'PasswordCrypt' => '8851c79988a095a9b529d8b92f96aeb1',
      'ShopId' => '50410',
      'AuthCode' => 'a6bdd62a0c'
      ) */
        ));

$config = array(
    'akkucentral' => array(
        'Username' => 'akkumulator',
        'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
        'ShopId' => '7171',
        'AuthCode' => '2b361baa70',
    ),
    'tokotveszek' => array(
        'Username' => 'akkumulator',
        'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
        'ShopId' => '93443',
        'AuthCode' => '5e5dc403f6',
    ),
    'akkutkeresek' => array(
        'Username' => 'akkumulator',
        'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
        'ShopId' => '20588',
        'AuthCode' => '7da1c1715c',
    ),
    'kapacitas' => array(
        'Username' => 'Wormsign',
        'PasswordCrypt' => '8851c79988a095a9b529d8b92f96aeb1',
        'ShopId' => '39862',
        'AuthCode' => 'e08564a155',
    )
);

$sql_con = array(
    'username' => 'wormsignh_worm',
    'password' => 'IxOn1985'
);

$config_db = array(
    'username' => $sql_con['username'],
    'password' => $sql_con['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_haffner',
    'dbtokshop' => 'tokshop'
);

$config_db_ak = array(
    'username' => $sql_con['username'],
    'password' => $sql_con['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_wormsign_hu',
);

$config_cel = array(
    'username' => $sql_con['username'],
    'password' => $sql_con['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_cellect',
    'dbtokshop' => 'products'
);

$config_ws = array(
    'username' => $sql_con['username'],
    'password' => $sql_con['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_wormsign_hu',
    'dbtokshop' => 'products'
);

$config_db_akkutkeresek = array(
    'username' => $sql_con['username'],
    'password' => $sql_con['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_wormtest',
);

$config_db_gigatel = array(
    'username' => $sql_con['username'],
    'password' => $sql_con['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_haffner',
    'select_db' => 'wormsignh_haffner',
);

$config_kapac = array(
    'username' => $sql_con['username'],
    'password' => $sql_con['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_kapacitas'
);

$config_vision = array(
    'username' => $sql_con['username'],
    'password' => $sql_con['password'],
    'connection' => 'mysql:host=localhost;dbname=wormsignh_vision'
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

//SQL adatbázis kiválasztása cs_kozos


try {
    $connection = new PDO($config_kapac['connection'], $config_kapac['username'], $config_kapac['password']);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}



//Táblák kiválasztása
function shop_table($connection) {
    $shop_tables = array(
        'wormsignh_kapacitas.dc'
    );
    return $shop_tables;
}

$tables = shop_table($connection);

function convert_table($connection) {
    $convert_tables = array(
        'dc'
    );
    return $convert_tables;
}

$conv_tables = convert_table($connection);

function all_prices($connection) {
    $prices = array(
        'all_prices',
    );
    return $prices;
}

$all_price = all_prices($connection);

//új termékek tábla kiválasztása

function shop_table_new($connection) {
    $prods = array(
        'new_products'
    );
    return $prods;
}

$new_prods = shop_table_new($connection);

$tables = array(
    'dc',
    'mobile',
    'gps',
    'shaver',
    'mp3',
    'tablet',
    'game',
    'survey'
);


$client = get_unas_client();

require_once dirname(__FILE__) . '/db.php';
