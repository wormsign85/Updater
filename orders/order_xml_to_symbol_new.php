<?php
header('content-type: text/xml');

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
/*
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
        $productid = $detail->addChild('productcode', $order_item_row['symbol_id']);
        $productname = $detail->addChild('productname', $order_item_row['Item_Name']);
        $quantity = $detail->addChild('quantity', $order_item_row['Item_Quantity']);
        $unipricenet = $detail->addChild('unipricenet', $order_item_row['Item_PriceNet']);
        $uniprice = $detail->addChild('uniprice', $order_item_row['Item_PriceGross']);
        $netvalue = $detail->addChild('netvalue', $unipricenet * $quantity);
        $grossvalue = $detail->addChild('grossvalue', $uniprice * $quantity);
    }
    $mustmanufacturing = $detail->addChild('mustmanufacturing');
    $allocate = $detail->addChild('allocate', '1');
    $detailstatus = $detail->addChild('detailstatus');
    $comment = $detail->addChild('comment');
}
*/

//Wormsign átvételek adatai innen következnek


$sql1 = "set names 'utf8'";
$sth = $conn->prepare($sql1);
$statement = $sth->execute();

$sql = "SELECT * FROM wormsignh_atvetel.ordering WHERE status='1'";
$sth = $conn->prepare($sql);
$statement = $sth->execute();
$orders = $sth->fetchAll();

$customerorders = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><customerorders/>');

/* $sql_status = " UPDATE orders SET symbol_status"
  . " VALUES :symbol_status"; */

$sql_id = 'SELECT * FROM wormsignh_atvetel.customer WHERE id=:customer_id';
$sql_order_item = 'SELECT * FROM wormsignh_atvetel.battery WHERE ordering_id=:generated_id';

foreach ($orders as $i => $orderingrow) {
    if (!$orderingrow['customer_id']) continue;
    
    $sth = $conn->prepare($sql_id);
    $sth->bindParam(':customer_id', $orderingrow['customer_id']);
    $sth->execute();
    $customerrow = $result = $sth->fetch(PDO::FETCH_ASSOC);

    if (!$customerrow) continue;
    
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
    $transportmode = $customerorder->addChild('transportmode');
    $vouchersequencecode = $customerorder->addChild('vouchersequencecode');
    $paymentmethod = $customerorder->addChild('paymentmethod');
    $paymentmethodtolerance = $customerorder->addChild('paymentmethodtolerance', '8');
    $comment = $customerorder->addChild('comment');
    $feedbackurl = $customerorder->addChild('feedbackurl', 'http://kereso.wormsign.hu/update/symbol/feedbackurl.php?id='
            . $orderingrow['generated_id'] . '&amp;text=');
    $errorurl = $customerorder->addChild('errorurl', 'http://kereso.wormsign.hu/update/symbol/order_error.php?errorid='
            . $orderingrow['generated_id'] . '&amp;text=');
    foreach ($order_items as $i2 => $order_item_row) {
        $detail = $customerorder->addChild('detail');
        $productid = $detail->addChild('productcode', $order_item_row['capacity']);
        $productname = $detail->addChild('productname');
        $quantity = $detail->addChild('quantity', '1');
        $unipricenet = $detail->addChild('unipricenet', $order_item_row['price']/1.27);
        $uniprice = $detail->addChild('uniprice', $order_item_row['price']);
        $netvalue = $detail->addChild('netvalue', $unipricenet * 1);
        $grossvalue = $detail->addChild('grossvalue', $uniprice * 1);
    }
    $mustmanufacturing = $detail->addChild('mustmanufacturing');
    $allocate = $detail->addChild('allocate', '0');
    $detailstatus = $detail->addChild('detailstatus');
    $comment = $detail->addChild('comment','Azonosító: ' . $order_item_row['primary_id']);
}

echo $customerorders->asXML();

file_put_contents('orders.xml',$customerorders->asXML());

//$link = mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
//if (!$link) {
//    die('Could not connect: ' . mysql_error());
//}
//
//mysql_select_db("wormsignh_mydb");
//
////require_once 'order_xml_to_crm.php';
//
//ata_mysql_query("
//UPDATE orders 
//SET symbol_status='1'", $link);
