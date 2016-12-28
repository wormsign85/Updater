<?php

///////////////////////////////////////////////
// init
$soap_server = "https://api.unas.eu/shop/?wsdl";
ini_set("soap.wsdl_cache_enabled", "0");
///////////////////////////////////////////////
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

try {
    $conn = new PDO('mysql:host=localhost;dbname=wormsignh_update', $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$sql1 = "set names 'utf8'";
$sth = $conn->prepare($sql1);
$statement = $sth->execute();

$sql_update = "SELECT * FROM `stock`";
$sth = $conn->prepare($sql_update);
$statement = $sth->execute();
$stock = $sth->fetchAll();

$stock_s = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Products/>');

foreach ($stock as $i => $row) {
    $product = $stock_s->AddChild('Product');
    $noerrorst = $product->AddChild('StopOnError', 'no');
    $sku = $product->AddChild('Sku', $row['rp_code']);
    $stocks = $product->AddChild('Stocks');
    $stock = $stocks->AddChild('Stock');
    $qty = $stock->AddChild('Qty', $row['free_stock']);
}
echo $stock_s->asXML();
file_put_contents('stocks_unas.xml',$stock_s->asXML());
try {
    $response = $client->setStockXML($auth, $stock_s->asXML());
    echo "<strong>setStock Response:</strong><br /> ";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
} catch (SoapFault $error) {
    echo "<strong>setStock Error:</strong><br /> ";
    echo "<pre>" . print_r($error, true) . "</pre>";
}
echo "<hr />";