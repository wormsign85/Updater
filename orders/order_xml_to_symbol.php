<?php

header('content-type: text/xml');
    require_once 'update_pro_sku.php';
$initfile = __DIR__ . '/../ujverzio/haffner/lib/init.php';
if (file_exists($initfile)) {
    // lokális
    require_once $initfile;

    $user = 'root';
    $pass = '';
} else {
    // éles
    require_once __DIR__ . '/lib/init.php';

    $user = 'wormsignh_worm';
    $pass = 'IxOn1985';
}

try {
    $conn = new PDO('mysql:host=localhost;dbname=wormsignh_mydb', $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}


$sql1 = "set names 'utf8'";
$sth = $conn->prepare($sql1);
$statement = $sth->execute();

$sql = "SELECT * FROM `orders` WHERE symbol_status='0'";
$sth = $conn->prepare($sql);
$statement = $sth->execute();
$orders = $sth->fetchAll();

$customerorders = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><customerorders/>');

/* $sql_status = " UPDATE orders SET symbol_status"
  . " VALUES :symbol_status"; */

$sql_id = 'SELECT unas_id FROM `customer` WHERE email=:email';
$sql_order_item = 'SELECT * FROM `orders_items` WHERE order_id=:order_id';

foreach ($orders as $i => $row) {
    $sth = $conn->prepare($sql_id);
    $sth->bindParam(':email', $row['customer_email']);
    $sth->execute();
    $customer_id = $result = $sth->fetchColumn();

    $sth = $conn->prepare($sql_order_item);
    $sth->bindParam(':order_id', $row['order_id']);
    $sth->execute();
    $order_items = $result = $sth->fetchAll();
    $customerorder = $customerorders->addChild('customerorder');
    $date = $customerorder->addChild('date', $row['date']);
    $expriationdays = $customerorder->addChild('expirationdays', '10');
    $orderid = $customerorder->addChild('orderid', $row['order_id']);
    $customer = $customerorder->addChild('customer', $customer_id);
    $customerid = $customerorder->addChild('customerid');
    $customercode = $customerorder->addChild('customercode');
    $country = $customerorder->addChild('country', $row['invoice_country']);
    $region = $customerorder->addChild('region', $row['invoice_county']);
    $zip = $customerorder->addChild('zip', $row['invoice_zip']);
    $city = $customerorder->addChild('city', $row['invoice_city']);
    $street = $customerorder->addChild('street', $row['invoice_street']);
    $housenumber = $customerorder->addChild('housenumber');
    $transportid = $customerorder->addChild('transportid');
    $transportname = $customerorder->addChild('transportname', $row['address_shipping_name']);
    $transportcountry = $customerorder->addChild('transportcountry', $row['shipping_country']);
    $transportregion = $customerorder->addChild('transportregion', $row['shipping_county']);
    $transportzip = $customerorder->addChild('transportzip', $row['shipping_zip']);
    $transportcity = $customerorder->addChild('transportcity', $row['shipping_city']);
    $transportstreet = $customerorder->addChild('transportstreet', $row['shipping_street']);
    $transporthousenumber = $customerorder->addChild('transporthousenumber');
    $transportcontactname = $customerorder->addChild('transportcontactname', $row['contact_name']);
    $currency = $customerorder->addChild('currency', $row['currency']);
    $currencyrate = $customerorder->addChild('currencyrate', '1');
    $transportmode = $customerorder->addChild('transportmode', $row['shipping_name']);
    $vouchersequencecode = $customerorder->addChild('vouchersequencecode');
    $paymentmethod = $customerorder->addChild('paymentmethod', $row['payment_name']);
    $paymentmethodtolerance = $customerorder->addChild('paymentmethodtolerance', '8');
    $comment = $customerorder->addChild('comment');
    $feedbackurl = $customerorder->addChild('feedbackurl', 'http://kereso.wormsign.hu/update/symbol/feedbackurl.php?id='
            . $row['order_id'] . '&amp;text=');
    $errorurl = $customerorder->addChild('errorurl', 'http://kereso.wormsign.hu/update/symbol/order_error.php?errorid='
            . $row['order_id'] . '&amp;text=');
    foreach ($order_items as $i2 => $order_item_row) {
        $detail = $customerorder->addChild('detail');
        if ($order_item_row['symbol_id']) {
            $productid = $detail->addChild('productcode', $order_item_row['symbol_id']);
        } else {
            $productid = $detail->addChild('productcode', $order_item_row['Item_Sku']);
        }
        $productname = $detail->addChild('productname', $order_item_row['Item_Name']);
        $quantity = $detail->addChild('quantity', $order_item_row['Item_Quantity']);
        $unipricenet = $detail->addChild('unipricenet', $order_item_row['Item_PriceNet']);
        $uniprice = $detail->addChild('uniprice', $order_item_row['Item_PriceGross']);
        $netvalue = $detail->addChild('netvalue', $unipricenet * $quantity);
        $grossvalue = $detail->addChild('grossvalue', $uniprice * $quantity);
        $mustmanufacturing = $detail->addChild('mustmanufacturing');
        $allocate = $detail->addChild('allocate', '1');
        $detailstatus = $detail->addChild('detailstatus');
        $comment = $detail->addChild('comment');
    }
}

//echo $customerorders->asXML();
//Wormsign átvételek adatai innen következnek

$result = ata_mysql_query("
UPDATE wormsignh_atvetel.battery pp
INNER JOIN wormsignh_atvetel.felujitas_symbol w ON(pp.capacity=w.kod)
SET pp.gyartando =w.gyartando
  ");

$sql1 = "set names 'utf8'";
$sth = $conn->prepare($sql1);
$statement = $sth->execute();

$sql = "SELECT * FROM wormsignh_atvetel.ordering WHERE status='4'";
$sth = $conn->prepare($sql);
$statement = $sth->execute();
$orders = $sth->fetchAll();


// $sql_status = " UPDATE orders SET symbol_status"
//  . " VALUES :symbol_status"; 

$sql_id = 'SELECT * FROM wormsignh_atvetel.customer WHERE id=:customer_id';
$sql_order_item = 'SELECT * FROM wormsignh_atvetel.battery WHERE ordering_id=:generated_id';
$sql_method = 'SELECT * FROM wormsignh_atvetel.ordering WHERE generated_id=:ordering_id';

$conv_shipping = array("0" => "GLS futárszolgálat", "1" => "GLS Csomagpontok", "2" => "Személyes átvétel szervizünkben",
    "3" => "Foxpost csomagautomaták", "4" => "Oda-vissza szállítás felújítás esetén");

$convpayment = array("0" => "Bankkártya", "1" => "Utánvéttel", "2" => "Átutalás",
    "3" => "Készpénz", "4" => "Bankkártyás fizetés az automatánál");


foreach ($orders as $i => $orderingrow) {
    if (!$orderingrow['customer_id'])
        continue;

    $sth = $conn->prepare($sql_id);
    $sth->bindParam(':customer_id', $orderingrow['customer_id']);
    $sth->execute();
    $customerrow = $result = $sth->fetch(PDO::FETCH_ASSOC);

    if (!$customerrow)
        continue;

    $sth = $conn->prepare($sql_order_item);
    $sth->bindParam(':generated_id', $orderingrow['generated_id']);
    $sth->execute();
    $order_items = $result = $sth->fetchAll();
    $customerorder = $customerorders->addChild('customerorder');
    $date = $customerorder->addChild('date', $orderingrow['delivery_date']);
    $expriationdays = $customerorder->addChild('expirationdays', '10');
    $orderid = $customerorder->addChild('orderid', $orderingrow['generated_id']);
    $customer = $customerorder->addChild('customer', $orderingrow['customer_id']);
    $customerid = $customerorder->addChild('customerid', $customerrow['sid']);
    $customercode = $customerorder->addChild('customercode', $customerrow['code']);
    $country = $customerorder->addChild('country', $customerrow['invoicecountry']);
    $region = $customerorder->addChild('region', $customerrow['invoicecountry']);
    $zip = $customerorder->addChild('zip', $customerrow['zip']);
    $city = $customerorder->addChild('city', $customerrow['city']);
    $street = $customerorder->addChild('street', $customerrow['address']);
    $housenumber = $customerorder->addChild('housenumber');
    $transportid = $customerorder->addChild('transportid');
    $transportname = $customerorder->addChild('transportname', $customerrow['contact_name']);
    $transportcountry = $customerorder->addChild('transportcountry');
    $transportregion = $customerorder->addChild('transportregion');
    $transportzip = $customerorder->addChild('transportzip', $customerrow['mailzip']);
    $transportcity = $customerorder->addChild('transportcity', $customerrow['mailcity']);
    $transportstreet = $customerorder->addChild('transportstreet', $customerrow['mailstreet']);
    $transporthousenumber = $customerorder->addChild('transporthousenumber');
    $transportcontactname = $customerorder->addChild('transportcontactname', $customerrow['contact_name']);
    $currency = $customerorder->addChild('currency', 'HUF');
    $currencyrate = $customerorder->addChild('currencyrate', '1');
    $transportmode = $customerorder->addChild('transportmode', strtr($orderingrow['delivery_type'], $conv_shipping));
    $vouchersequencecode = $customerorder->addChild('vouchersequencecode');
    $paymentmethod = $customerorder->addChild('paymentmethod', strtr($orderingrow['payment_method'], $convpayment));
    $paymentmethodtolerance = $customerorder->addChild('paymentmethodtolerance', '8');
    $comment = $customerorder->addChild('comment', $orderingrow['comment']);
    $feedbackurl = $customerorder->addChild('feedbackurl', 'http://kereso.wormsign.hu/update/symbol/feedbackurl.php?id='
            . $orderingrow['generated_id'] . '&amp;text=');
    $errorurl = $customerorder->addChild('errorurl', 'http://kereso.wormsign.hu/update/symbol/order_error.php?errorid='
            . $orderingrow['generated_id'] . '&amp;text=');
    foreach ($order_items as $i2 => $order_item_row) {
        $detail = $customerorder->addChild('detail');
        $productid = $detail->addChild('productcode', $order_item_row['capacity']);
        $productname = $detail->addChild('productname');
        $quantity = $detail->addChild('quantity', '1');
        $unipricenetvalue = isset($order_item_row['price']) ? ($order_item_row['price'] / 1.27) : '0';
        $unipricevalue = isset($order_item_row['price']) ? ($order_item_row['price']) : '0';

        //Be akartuk álltani, ha nincs ár, ne legyen ilen nod, de nem engedi, igy 0-t adunk neki, vagy 
        //a tényleges letöltött árats
        if ('' !== $unipricenetvalue) {
            $unipricenet = $detail->addChild('unipricenet', $unipricenetvalue);
            $uniprice = $detail->addChild('uniprice', $unipricevalue);
        }
        $netvalue = $detail->addChild('netvalue', $unipricenetvalue);
        $grossvalue = $detail->addChild('grossvalue', $unipricevalue);
        $comment = $detail->addChild('comment', 'Azonosító: ' . $order_item_row['primary_id'] . ' ' . $order_item_row['description']);
        $mustmanufacturing = $detail->addChild('mustmanufacturing', $order_item_row['gyartando']);
        $allocate = $detail->addChild('allocate', '0');
        $detailstatus = $detail->addChild('detailstatus');
    }
}

echo $customerorders->asXML();

file_put_contents('orders.xml', $customerorders->asXML());


$result = ata_mysql_query("UPDATE wormsignh_mydb.orders 
SET symbol_status='1'
       ");

$result = ata_mysql_query("UPDATE wormsignh_atvetel.ordering 
SET status='3' WHERE status = '4'
       ");
