<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

set_time_limit(1000);

try {
    $conn = new PDO($config_db_stock['connection'], $config_db_stock['username'], $config_db_stock['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

function set_price_unas($client, $systemConfig, $soapConfig, $webshopName, $pdo_conn) {

    $t = time();
    $logfilename = $systemConfig['log_dir'] . '/' . $webshopName . '_' . $soapConfig['ShopId'];


    $select_all = "SELECT * FROM wormsignh_wormsign_hu.tps_webshop "
//            . "Where xrefid = '3030' ";
            . "Where category = '3231' ";

//    $select_all = "SELECT * FROM wormsignh_update.full_stock "
//            . "Where xrefid = '3030' ";
//            . "Where cameron_sku LIKE 'CS-%' AND free_stock!= '0' ";
    // header('content-type: text/xml');
    //$xml = file_get_contents('prices.xml');


    $header = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Products/>');

    $statement = $pdo_conn->prepare($select_all);
    $statement->execute();
    $rows = $statement->fetchAll();


    foreach ($rows as $i => $row) {

        $product = $header->AddChild('Product');
        $noerrorst = $product->AddChild('StopOnError', 'no');
        $sku = $product->AddChild('Sku', $row['id']);
        $statuses = $product->AddChild('Statuses');
        $status = $statuses->AddChild('Status');
        $type = $status->AddChild('Type', 'base');
        $value = $status->AddChild('Value', '1');
        $prices = $product->AddChild('Prices');
        $price = $prices->AddChild('Price');
        $priceType = $price->AddChild('Type', 'normal');
        $net = $price->AddChild('Net', $row['ar'] / 1.27);
        $gross = $price->AddChild('Gross', $row['ar']);
    }
//    
//        foreach ($rows as $i => $row) {
//
//        $product = $header->AddChild('Product');
//        $noerrorst = $product->AddChild('StopOnError', 'no');
//        $sku = $product->AddChild('Sku', $row['productcode']);
//        $statuses = $product->AddChild('Statuses');
//        $status = $statuses->AddChild('Status');
//        $type = $status->AddChild('Type', 'base');
//        $value = $status->AddChild('Value', '0');
//        $specprices = $product->AddChild('Price');
//        $priceType = $specprices->AddChild('Type', 'sale');
//        $net = $specprices->AddChild('Net', '10000');
//        $gross = $specprices->AddChild('Gross', '10000');
//    }

    log_unas($logfilename, 'SQL SECONDS: ' . (time() - $t));

    $t = time();
    echo $header->asXML();

    if (!empty($_GET['test'])) {
        return;
    }

    try {
        $auth = array(
            'Username' => $soapConfig['Username'],
            'PasswordCrypt' => $soapConfig['PasswordCrypt'],
            'ShopId' => $soapConfig['ShopId'],
            'AuthCode' => $soapConfig['AuthCode']
        );

        // Tesztadatok cseréje
        // file_put_contents ('unas_order.xml', $response);

        $response = $client->setProductXML($auth, $header->asXML());
    } catch (SoapFault $error) {
        log_unas($logfilename, 'SoapError: ' . $error->getMessage());
        echo "<strong>getOrder Error:</strong><br /> ";
        echo "<strong>setProductXML Response:</strong><br /> ";
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
        echo "<pre>" . print_r($error, true) . "</pre>";
    }
    file_put_contents('response.xml', $response);
//
//    file_put_contents('prices.xml', $header);

    log_unas($logfilename, 'SOAP SECONDS: ' . (time() - $t));
}

foreach ($config['unas_soap'] as $webshopName => $soapConfig) {
    set_price_unas($client, $config['system'], $soapConfig, $webshopName, $pdo_conn);
}
//itt újraaktiváljuk azokat, amik készleten vannak ismét
$ujrakeszleten = " UPDATE wormsignh_update.full_stock
 SET inaktivalva = 'nem'
WHERE inaktivalva='igen' AND free_stock != '0' ";

//itt inaktiváljuk amiket feltöltöttünk, vagyis inaktiváltunk unason
$nullakeszlet = " UPDATE wormsignh_update.full_stock pp
 SET inaktivalva = 'igen'
WHERE inaktivalva='nem' AND free_stock = '0'";

$keszletoff = " UPDATE wormsignh_update.full_stock pp
    inner join wormsignh_wormsign_hu.tps_webshop w ON(pp.cameron_sku = w.cameron_sku)
 SET pp.inaktivalva = w.keszlet WHERE w.keszlet='off' ";

$keszletoff1 = " UPDATE wormsignh_update.full_stock pp
    inner join wormsignh_wormsign_hu.tps_webshop_img w ON(pp.cameron_sku = w.cameron_sku)
 SET pp.inaktivalva = w.keszlet WHERE w.keszlet='off' ";

//update full_stock
//set nullakeszlet = 'off'
//where category LIKE '%porszívó%'
//        gardena
//        fogkefe

try {
    $sth = $conn->prepare($ujrakeszleten);
    $statement = $sth->execute();

    $sth = $conn->prepare($nullakeszlet);
    $statement = $sth->execute();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}