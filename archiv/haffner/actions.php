<?php

require_once 'init.php';

//SQL kapcsolat létrehozása

function mysqli($config_db) {
    $conn = mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
    if (!$conn) {
        die('Could not connect: ' . mysql_error());
    }
    echo'Connected successfully <br/>';

    return $conn;
}

$connection = mysqli($config_db);

//SQL adatbázis kiválasztása cs_kozos
/*
function select_db($connection) {
    $db_selected = mysql_select_db('cs_kozos');
    if (!$db_selected) {
        die('Can\'t use cs_kozos : ' . mysql_error());
    }
    return $db_selected;
}

$select_db = select_db($connection);
*/

/*
//Táblák kiválasztása
function shop_table($connection, $select_db) {
    $shop_tables = array(
        'mobile',
        'dc',
        'gps',
        'medical',
        'pda',
        'laptop',
        'tablet',
        'baby',
        'dog'
    );
    return $shop_tables;
}

$tables = shop_table($connection, $select_db);

function all_prices($connection, $select_db) {
    $prices = array(
        'all_prices',
    );
    return $prices;
}

$all_price = all_prices($connection, $select_db);

//új termékek tábla kiválasztása

function shop_table_new($connection, $select_db) {
    $prods = array(
        'new_products'
    );
    return $prods;
}

$new_prods = shop_table_new($connection, $select_db);










function pdo_connection($config_db_sku) {
    try {
        $conn = new PDO($config_db_sku['connection'], $config_db_sku['username'], $config_db_sku['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    return $conn;
}

$pdo_conn = pdo_connection($config_db_sku);

//log error mentése

function log_unas($filename, $message) {
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

*/