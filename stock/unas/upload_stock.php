<?php

//$initfile = __DIR__ . '/../../lib/init.php';
//if (file_exists($initfile)) {
//    // lokális
//    require $initfile;
//
//    $user = 'root';
//    $pass = '';
//} else {
//    // éles
//    require __DIR__ . '/../../lib/init.php';
//
//    $user = 'wormsignh_worm';
//    $pass = 'IxOn1985';
//
////    require_once '../orders/get_orders.php';
////
////    require_once '../customers/get_customers.php';
//}

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


try {
    $conn = new PDO($config_db_my['connection'], $config_db_my['username'], $config_db_my['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
//először feltöltjük azokat, amiknek van xrefid-jük, vagyis generált termék, nem önálló

$sql_select = " SELECT * "
        . " FROM wormsignh_update.full_stock "
        . " WHERE free_stock!=keszlet_unas AND xrefid != '' ";
//tps_webshopban levő készlet feltöltése
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
    $sku = $product->AddChild('Sku', $row['xrefid']);
    $stocks = $product->AddChild('Stocks');
    $stock = $stocks->AddChild('Stock');
    $qty = $stock->AddChild('Qty', $row['quantity'] + $row['kp_quantity'] - $row['nonstrictallocate']);
}

//Most azokét töltjük fel, aminek nincs xrefidje, vagyis önálló termék

$sql_select1 = " SELECT * "
        . " FROM wormsignh_update.full_stock "
        . " WHERE free_stock!=keszlet_unas AND xrefid = '' ";

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
    $sku = $product->AddChild('Sku', $row['productcode']);
    $stocks = $product->AddChild('Stocks');
    $stock = $stocks->AddChild('Stock');
    $qty = $stock->AddChild('Qty', $row['quantity'] + $row['kp_quantity'] - $row['nonstrictallocate']);
}


//itt feltöltjük azokat, amiket még nem töltöttünk fel de 0 a készletük, ha feltöltöttük, beirjuk
// a nullakeszlet mezobe, hogy igen, innentől nem töltjük fel, ami nulla
$sql_select2 = " SELECT * "
        . " FROM wormsignh_update.full_stock "
        . " WHERE free_stock='0' AND keszlet_unas = '0'  AND nullakeszlet != 'igen' ";

try {
    $sth = $conn->prepare($sql_select2);
    $statement = $sth->execute();
    $stock = $sth->fetchAll();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
foreach ($stock as $i => $row) {
    $product = $stock_s->AddChild('Product');
    $noerrorst = $product->AddChild('StopOnError', 'no');
    $sku = $product->AddChild('Sku', $row['productcode']);
    $stocks = $product->AddChild('Stocks');
    $stock = $stocks->AddChild('Stock');
    $qty = $stock->AddChild('Qty', $row['quantity'] + $row['kp_quantity'] - $row['nonstrictallocate']);
}

$sql_select3 = " SELECT * "
        . " FROM wormsignh_update.full_stock "
        . " WHERE free_stock='0' AND keszlet_unas = '0'  AND nullakeszlet != 'igen' AND xrefid != '0' ";


try {
    $sth = $conn->prepare($sql_select3);
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
    $qty = $stock->AddChild('Qty', $row['quantity'] + $row['kp_quantity'] - $row['nonstrictallocate']);
}
    
echo $stock_s->asXML();
file_put_contents('stock_fel_unas.xml', $stock_s->asXML());
try {
    $response = $client->setStockXML($auth, $stock_s->asXML());
//    echo "<strong>setStock Response:</strong><br /> ";
//    echo "<pre>" . htmlspecialchars($response) . "</pre>";
} catch (SoapFault $error) {
//    echo "<strong>setStock Error:</strong><br /> ";
//    echo "<pre>" . print_r($error, true) . "</pre>";
}
//echo "<hr />";
//most pedig jelöljük, hogy feltöltöttük a készletet unasra, igy csak akkor töltődik majd fel megint
//ha vmi változott

$sql_update1 = " UPDATE wormsignh_update.full_stock "
        . " SET keszlet_unas=free_stock, keszletdate_unas = current_timestamp";

try {
    $sth = $conn->prepare($sql_update1);
    $statement = $sth->execute();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}


$sql_nullakeszlet = " UPDATE wormsignh_update.full_stock "
        . " SET nullakeszlet= 'igen' WHERE free_stock = '0' ";

try {
    $sth = $conn->prepare($sql_nullakeszlet);
    $statement = $sth->execute();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}

$sql_keszlet = " UPDATE wormsignh_update.full_stock "
        . " SET nullakeszlet= 'nem' WHERE free_stock != '0' ";

try {
    $sth = $conn->prepare($sql_nullakeszlet);
    $statement = $sth->execute();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}