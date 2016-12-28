<?php
//require_once 'import1.php';
///////////////////////////////////////////////
// init
$soap_server = "https://api.unas.eu/shop/?wsdl";
ini_set("soap.wsdl_cache_enabled", "0");
///////////////////////////////////////////////
// auth
$auth = array(
    'Username' => 'akkumulator',
    'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
    'ShopId' => '20588',
    'AuthCode' => '7da1c1715c'
);
///////////////////////////////////////////////
// connect
$client = new SoapClient($soap_server);

header('content-type: text/xml');

$user = 'root';
$pass = '';

try {
    $conn = new PDO('mysql:host=localhost', $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$sql1 = "set names 'utf8'";
$sth = $conn->prepare($sql1);
$statement = $sth->execute();

$sql_update1 = "SELECT * FROM wormsignh_haffner.sajat_termekek pp"
        . " INNER JOIN wormsignh_update.stock w on(pp.cameron_sku=w.cameron_sku)";


$sql_update = "SELECT szla_id, stock FROM wormsignh_haffner.sajat_termekek WHERE szla_id!=''";
$sql_stock = "UPDATE wormsignh_wormtest.tps_webshop p"
        . "INNER JOIN wormsignh_haffner.sajat_termekek w ON(p.cameron_sku=w.sku)"
        . "SET p.haff_keszlet=w.stock";

try {
    $sth = $conn->prepare($sql_update);
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
    $sku = $product->AddChild('Sku', $row['szla_id']);
    $stocks = $product->AddChild('Stocks');
    $stock = $stocks->AddChild('Stock');
    $qty = $stock->AddChild('Qty', $row['stock']);
}
echo $stock_s->asXML();
file_put_contents('stocks_unas_ak.xml',$stock_s->asXML());
try {
    $response = $client->setStockXML($auth, $stock_s->asXML());
//    echo "<strong>setStock Response:</strong><br /> ";
//    echo "<pre>" . htmlspecialchars($response) . "</pre>";
} catch (SoapFault $error) {
//    echo "<strong>setStock Error:</strong><br /> ";
//    echo "<pre>" . print_r($error, true) . "</pre>";
}
//echo "<hr />";
