<?php

$initfile = __DIR__ . '/../lib/init.php';
if (file_exists($initfile)) {
    // lokális
    require_once $initfile;

    $user = 'root';
    $pass = '';
} else {
    // éles
    require_once __DIR__ . '/../lib/init.php';

    $user = 'wormsignh_worm';
    $pass = 'IxOn1985';

//    require_once '../orders/get_orders.php';
//
//    require_once '../customers/get_customers.php';
}


set_time_limit(600);
//connect to the database 
try {
    $conn = new PDO($config_db_stock['connection'], $config_db_stock['username'], $config_db_stock['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$curl_user_pass = array(
    'url' => 'https://b2b.akku.hu/data/ar-keszlet.xml',
    'user_pass' => 'unas:g3r0n1m0'
);

function get_stock_price_url($curl_user_pass) {
    // URL to login page
    $url = $curl_user_pass['url'];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_FILE, $out);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, $curl_user_pass['user_pass']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $xml = curl_exec($ch);
    return $xml;
}

$get_xml = get_stock_price_url($curl_user_pass);
//
//$connect = mysql_connect("localhost", $config_db['username'], $config_db['password']);
//mysql_select_db("wormsignh_kapacitas", $conn); //select the table
//header('Content-Type: text/html; charset=utf-8');
//$csv = file_get_contents('export-wormsign.csv');

ata_mysql_query("set names 'utf8'", $connection);

$stocks1 = new SimpleXMLElement($get_xml);

$sqlutf = "set names 'utf8'";
$sth = $conn->prepare($sqlutf);
$statement = $sth->execute();

$sql = " UPDATE full_stock SET"
        . " kp_quantity=:keszlet, kp_modified = current_timestamp "
        . " WHERE cameron_sku = :sku1 OR cameron_sku = :sku2 ";


foreach ($stocks1->termekek->termek as $prods => $termek) {
    $sku = $termek->termek_sku;
    $keszlet = $termek->keszlet;

    $q = $conn->prepare($sql);
    $q->execute(array(
        ':keszlet' => $keszlet,
        ':sku1' => $sku,
        ':sku2' => 'CS-' . $sku // CS- előtaggal is találja meg
    ));
}

//$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
//  SET vasarolhato_ha_nincs_raktaron='1'
//  WHERE megnevezes LIKE '%felújítás%' OR megnevezes LIKE '%pakk%'
//  ");

require_once '../stock/unas/upload_stock.php';