<?php

///////////////////////////////////////////////
// init
$soap_server = "https://api.unas.eu/shop/?wsdl";
ini_set("soap.wsdl_cache_enabled", "0");
///////////////////////////////////////////////
// auth
$auth = array(
    'Username' => 'mrdezz',
    'PasswordCrypt' => '32cb61643dc09e9d849b79361bb244b5',
    'ShopId' => '50410',
    'AuthCode' => 'a6bdd62a0c'
);
///////////////////////////////////////////////
// connect
$client = new SoapClient($soap_server);

///////////////////////////////////////////////
//getOrder
try {
    $params = array(
        'InvoiceStatus' => 0,
        //'InvoiceAutoSet' => 2,
        'DateStart' => "2014.10.01",
        'DateEnd' => "2020.12.31"
    );
    $response = $client->getOrder($auth, $params);
} catch (SoapFault $error) {
    echo "<strong>getOrder Error:</strong><br /> ";
    echo "<pre>" . print_r($error, true) . "</pre>";
}

$user = 'root';
$pass = '';

try {
    $conn = new PDO('mysql:host=localhost;dbname=mydb', $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
$orders = new SimpleXMLElement($response);

$sqlutf = "set names 'utf8'";
$sth = $conn->prepare($sqlutf);
$statement = $sth->execute();

$sql = "INSERT IGNORE INTO orders (order_id,date,customer_email,"
//customer_datas
        . " customer_username,contact_name,contact_phone,contact_mobile,contact_lang,"
//invoice_datas
        . " invoice_name,invoice_zip,invoice_city,invoice_street,invoice_county,invoice_country,invoice_countrycode,invoice_taxnumber,"
//shipping_datas
        . " address_shipping_name,shipping_zip,shipping_city,shipping_street,shipping_county,shipping_country,"
        . " shipping_countrycode,"
//status, payment
        . " currency,status,payment_id,payment_name,payment_type,payment_status,"
        . " shipping_id,shipping_name,invoice_status,invoice_statustext,invoice_number)"
        . "VALUES (:order_id,:date,:customer_email,"
//customer_datas
        . " :customer_username,:contact_name,:contact_phone,:contact_mobile,:contact_lang,"
//invoice_datas
        . " :invoice_name,:invoice_zip,:invoice_city,:invoice_street,:invoice_county,:invoice_country,:invoice_countrycode,:invoice_taxnumber,"
//shipping_datas
        . " :address_shipping_name,:shipping_zip,:shipping_city,:shipping_street,:shipping_county,:shipping_country,"
        . " :shipping_countrycode,"
//status, payment
        . " :currency,:status,:payment_id,:payment_name,:payment_type,:payment_status,"
        . " :shipping_id,:shipping_name,:invoice_status,:invoice_statustext,:invoice_number)";


$sql_id = 'SELECT unas_id FROM `customer` WHERE email=:email';


$sql1 = " INSERT IGNORE INTO orders_items (order_id,Item_Id,Item_Sku,Item_Name,Item_Unit,Item_Quantity,Item_PriceNet,Item_PriceGross,Item_Vat)"
        . " VALUES (:order_id,:Item_Id,:Item_Sku,:Item_Name,:Item_Unit,:Item_Quantity,:Item_PriceNet,:Item_PriceGross,:Item_Vat)";

$sql_customers = " INSERT IGNORE INTO customer (unas_id,name,country,region,zip,city,street,mailcountry,mailregion,mailzip,mailcity,mailstreet,email,phone)"
        . " VALUES (:unas_id,:name,:country,:region,:zip,:city,:street,:mailcountry,:mailregion,:mailzip,:mailcity,:mailstreet,:email,:phone)";



foreach ($orders->Order as $sorszam => $order) {
    $order_id = $order->Key;
    $date = $order->Date;
    $customer_email = $order->Customer->Email;

    $sth = $conn->prepare($sql_id);
    $sth->bindParam(':email', $customer_email);
    $sth->execute();
    $customer_id = $result = $sth->fetchColumn();

    $customer_username = $order->Customer->Username;
    $contact_name = $order->Customer->Contact->Name;
    $contact_phone = $order->Customer->Contact->Phone;
    $contact_mobile = $order->Customer->Contact->Mobile;
    $contact_lang = $order->Customer->Contact->Lang;
    $invoice_name = $order->Customer->Addresses->Invoice->Name;
    $invoice_zip = $order->Customer->Addresses->Invoice->ZIP;
    $invoice_city = $order->Customer->Addresses->Invoice->City;
    $invoice_street = $order->Customer->Addresses->Invoice->Street;
    $invoice_county = $order->Customer->Addresses->Invoice->County;
    $invoice_country = $order->Customer->Addresses->Invoice->Country;
    $invoice_countrycode = $order->Customer->Addresses->Invoice->Countrycode;
    $invoice_taxnumber = $order->Customer->Addresses->Invoice->Taxnumber;
    $address_shipping_name = $order->Customer->Addresses->Shipping->Name;
    $shipping_zip = $order->Customer->Addresses->Shipping->ZIP;
    $shipping_city = $order->Customer->Addresses->Shipping->City;
    $shipping_street = $order->Customer->Addresses->Shipping->Street;
    $shipping_county = $order->Customer->Addresses->Shipping->County;
    $shipping_country = $order->Customer->Addresses->Shipping->Country;
    $shipping_countrycode = $order->Customer->Addresses->Shipping->Countrycode;
    $currency = $order->Currency;
    $status = $order->Status;
    $payment_id = $order->Payment->Id;
    $payment_name = $order->Payment->Name;
    $payment_type = $order->Payment->Type;
    $payment_status = $order->Payment->Status;
    $shipping_id = $order->Shipping->Id;
    $shipping_name = $order->Shipping->Name;
    $invoice_status = $order->Invoice->Status;
    $invoice_statustext = $order->Invoice->Statustext;
    $invoice_number = $order->Invoice->Number;
    if (!$customer_id) {
        $customer_id = uniqid();
        $q1 = $conn->prepare($sql_customers);
        $q1->execute(array(
            ':unas_id' => $customer_id,
            ':name' => $invoice_name,
            ':country' => $invoice_country,
            ':region' => $invoice_county,
            ':zip' => $invoice_zip,
            ':city' => $invoice_city,
            ':street' => $invoice_street,
            ':mailcountry' => $shipping_country,
            ':mailregion' => $shipping_county,
            ':mailzip' => $shipping_zip,
            ':mailcity' => $shipping_city,
            ':mailstreet' => $shipping_street,
            ':email' => $customer_email,
            ':phone' => $contact_phone,
        ));
    }
    $q = $conn->prepare($sql);
    $q->execute(array(
        ':order_id' => $order_id,
        ':date' => $date,
        ':customer_email' => $customer_email,
        ':customer_username' => $customer_username,
        ':contact_name' => $contact_name,
        ':contact_phone' => $contact_phone,
        ':contact_mobile' => $contact_mobile,
        ':contact_lang' => $contact_lang,
        ':invoice_name' => $invoice_name,
        ':invoice_zip' => $invoice_zip,
        ':invoice_city' => $invoice_city,
        ':invoice_street' => $invoice_street,
        ':invoice_county' => $invoice_county,
        ':invoice_country' => $invoice_country,
        ':invoice_countrycode' => $invoice_countrycode,
        ':invoice_taxnumber' => $invoice_taxnumber,
        ':address_shipping_name' => $address_shipping_name,
        ':shipping_zip' => $shipping_zip,
        ':shipping_city' => $shipping_city,
        ':shipping_street' => $shipping_street,
        ':shipping_county' => $shipping_county,
        ':shipping_country' => $shipping_country,
        ':shipping_countrycode' => $shipping_countrycode,
        ':currency' => $currency,
        ':status' => $status,
        ':payment_id' => $payment_id,
        ':payment_name' => $payment_name,
        ':payment_type' => $payment_type,
        ':payment_status' => $payment_status,
        ':shipping_id' => $shipping_id,
        ':shipping_name' => $shipping_name,
        ':invoice_status' => $invoice_status,
        ':invoice_statustext' => $invoice_statustext,
        ':invoice_number' => $invoice_number
    ));
}
foreach ($orders->Order as $sorszam => $order) {
    $order_id1 = $order->Key;
    foreach ($order->Items->Item as $itemKey => $item) {
        $Item_Id = $item->Id;
        $Item_Sku = $item->Sku;
        $Item_Name = $item->Name;
        $Item_Unit = $item->Unit;
        $Item_Quantity = $item->Quantity;
        $Item_PriceNet = $item->PriceNet;
        $Item_PriceGross = $item->PriceGross;
        $Item_Vat = $item->Vat;
        $q1 = $conn->prepare($sql1);
        $q1->execute(array(
            ':order_id' => $order_id1,
            ':Item_Id' => $Item_Id,
            ':Item_Sku' => $Item_Sku,
            ':Item_Name' => $Item_Name,
            ':Item_Unit' => $Item_Unit,
            ':Item_Quantity' => $Item_Quantity,
            ':Item_PriceNet' => $Item_PriceNet,
            ':Item_PriceGross' => $Item_PriceGross,
            ':Item_Vat' => $Item_Vat
        ));
    }
}

file_put_contents ('orders.xml',SimpleXMLElement($response)());
//include 'get_orders_unas_akkutkeresek.php';
//include 'update_pro_sku.php';
