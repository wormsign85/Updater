<?php
set_time_limit(600);
///////////////////////////////////////////////
// init
$soap_server = "https://api.unas.eu/shop/?wsdl";
ini_set("soap.wsdl_cache_enabled", "0");
/////////////////////////////////////////////
// auth
$auth = array(
        'Username' => 'akkumulator',
        'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
        'ShopId' => '7171',
        'AuthCode' => '2b361baa70'
);
///////////////////////////////////////////////
// connect
$client = new SoapClient($soap_server);

header('content-type: text/xml');

$user = 'wormsignh_worm';
$pass = 'IxOn1985';

//$user = 'root';
//$pass = '';

try {
    $conn = new PDO('mysql:host=localhost', $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$sql_select = " SELECT id, szla_id, keszlet "
        . " FROM wormsignh_wormsign_hu.tps_webshop"
        . " WHERE keszlet!=keszlet_unas";


try {
    $sth = $conn->prepare($sql_select);
    $statement = $sth->execute();
    $stock = $sth->fetchAll();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}



$stock_s = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Products/>');

foreach ($stock as $i => $row) {
    $product = $stock_s->AddChild('Product');
    $noerrorst = $product->AddChild('StopOnError', 'no');
    $sku = $product->AddChild('Sku', $row['id']);
    $stocks = $product->AddChild('Stocks');
    $stock = $stocks->AddChild('Stock');
    $qty = $stock->AddChild('Qty', $row['keszlet']);
}


$sql_select1 = " SELECT id, szla_id, keszlet, xrefid "
        . " FROM wormsignh_wormsign_hu.tps_webshop_new"
        . " WHERE keszlet!=keszlet_unas";

try {
    $sth = $conn->prepare($sql_select1);
    $statement = $sth->execute();
    $stock = $sth->fetchAll();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
foreach ($stock as $i => $row) {
    $product = $stock_s->AddChild('Product');
    $noerrorst = $product->AddChild('StopOnError', 'no');
    $sku = $product->AddChild('Sku', $row['xrefid']);
    $stocks = $product->AddChild('Stocks');
    $stock = $stocks->AddChild('Stock');
    $qty = $stock->AddChild('Qty', $row['keszlet']);
}




echo $stock_s->asXML();
file_put_contents('stocks_unas_ac.xml',$stock_s->asXML());
try {
    $response = $client->setStockXML($auth, $stock_s->asXML());
//    echo "<strong>setStock Response:</strong><br /> ";
//    echo "<pre>" . htmlspecialchars($response) . "</pre>";
} catch (SoapFault $error) {
//    echo "<strong>setStock Error:</strong><br /> ";
//    echo "<pre>" . print_r($error, true) . "</pre>";
}
//echo "<hr />";



$sql_update1 = " UPDATE wormsignh_wormsign_hu.tps_webshop "
        . " SET keszlet_unas=keszlet, keszletdate_unas = current_timestamp" ;

try {
    $sth = $conn->prepare($sql_update1);
    $statement = $sth->execute();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}


$sql_update2 = " UPDATE wormsignh_wormsign_hu.tps_webshop_new "
        . " SET keszlet_unas=keszlet, keszletdate_unas = current_timestamp";

//
try {
    $sth = $conn->prepare($sql_update2);
    $statement = $sth->execute();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
