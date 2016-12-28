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
    'ShopId' => '41731',
    'AuthCode' => 'd38efd0c5c'
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

$sql_update = "SELECT * FROM `stock` WHERE rp_code!=0";
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

$link = mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("wormsignh_wormtest");

/*
mysql_query("UPDATE wormsignh_wormtest.tps_webshop pp
        INNER JOIN wormsignh_wormtest.tps_webshop_feltolt w ON(w.cameron_sku=pp.cameron_sku)
        SET pp.keszlet_ws=w.keszlet_ws",
 $link);
*/
