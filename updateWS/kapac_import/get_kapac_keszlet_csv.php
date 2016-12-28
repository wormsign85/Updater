<?php

require_once '../lib/init.php';
set_time_limit(600);
//connect to the database 
try {
    $conn = new PDO($config_db_wormsign['connection'], $config_db_wormsign['username'], $config_db_wormsign['password']);
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

$sql = "UPDATE wormsignh_wormsign_hu.tps_webshop SET"
        . " keszlet=:keszlet, keszletdate = current_timestamp,"
        . " keszlet_new=:keszlet, keszletdate_new = current_timestamp"
        . " WHERE cameron_sku = :sku1 OR cameron_sku = :sku2";


foreach ($stocks1->termekek->termek as $prods => $termek) {
    $sku = $termek->termek_sku;
    $keszlet = $termek->keszlet;

    $q = $conn->prepare($sql);
    $q->execute(array(
        ':keszlet' => $keszlet,
        ':keszlet_new' => $keszlet,
        ':sku1' => $sku,
        ':sku2' => 'CS-' . $sku // CS- előtaggal is találja meg
    ));
}

$sql1 = "UPDATE wormsignh_wormsign_hu.tps_webshop_new SET"
        . " keszlet=:keszlet, keszletdate = current_timestamp,"
        . " keszlet_new=:keszlet, keszletdate_new = current_timestamp"
        . " WHERE cameron_sku = :sku1 OR cameron_sku = :sku2";


foreach ($stocks1->termekek->termek as $prods => $termek) {
    $sku = $termek->termek_sku;
    $keszlet = $termek->keszlet;

    $q = $conn->prepare($sql1);
    $q->execute(array(
        ':keszlet' => $keszlet,
        ':keszlet_new' => $keszlet,
        ':sku1' => $sku,
        ':sku2' => 'CS-' . $sku // CS- előtaggal is találja meg
    ));
}

//$sql1 = " UPDATE wormsignh_wormsign_hu.tps_webshop_img SET"
//        . " keszlet=:keszlet, keszletdate = current_timestamp,"
//        . " WHERE cameron_sku = :sku2";
//
//foreach ($stocks1->termekek->termek as $prods => $termek) {
//    $sku = $termek->termek_sku;
//    $keszlet = $termek->keszlet;
//
//    $q = $conn->prepare($sql1);
//    $q->execute(array(
//        ':keszlet' => $keszlet,
//        ':sku2' => 'CS-' . $sku // CS- előtaggal is találja meg
//    ));
//}
//echo '<br/><br/>Kapacitás készlet és ár importálása <br/><br/>';


/*
  $result = ata_mysql_query("UPDATE wormsignh_wormtest.tps_webshop
  SET elerhetoseg=IF(keszlet_kapac>=0,'Külső készleten - 5 munkanap','')
  ");

  $result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET active=IF(keszletdate_new<'" . date('Y-m-d H:i:s', time() - 1 * 24 * 3600) . "','0','1')
  WHERE kapac_category!=''
  ");
 */

//$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop_new pp"
//        . " INNER JOIN wormsignh_wormsign_hu.tps_webshop w ON(pp.cameron_sku=w.cameron_sku)"
//        . " SET pp.keszlet=w.keszlet, pp.keszletdate = w.keszletdate  "
//);

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET active=1
  WHERE kapac_category!=''
  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET active=IF(keszletdate_new<'" . date('Y-m-d H:i:s', time() - 1 * 24 * 3600) . "','0','1')
  WHERE kapac_category!=''
  ");

//$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
//  SET leiras_hosszu='<p>Vállaljuk nagy mennyiségű ólom akkumulátorok szállítását, telepítését is.</p>
//<p>&nbsp; </p>
//<p>Amennyiben gyári adatlapra, specifikációkra van szüksége kérem, jelezze ezt nekünk a cikkszám megjelölésével.</p>
//<p>Ha műszaki kérdése merülne fel, készséggel állunk rendelkezésére! </p>'
//  WHERE kapac_category LIKE '%ólom%'
//  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET vasarolhato_ha_nincs_raktaron='1'
  WHERE megnevezes LIKE '%felújítás%' OR megnevezes LIKE '%pakk%'
  ");

//
require_once '../stock_upload_akkucentral.php';
require_once '../stock_upload_akkucentral_1.php';