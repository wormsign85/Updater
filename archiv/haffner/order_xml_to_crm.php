<?php

header('content-type: text/xml');

$user = 'wormsignh_worm';
$pass = 'IxOn1985';



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

$customerorders = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Projects/>');

/* $sql_status = " UPDATE orders SET symbol_status"
  . " VALUES :symbol_status"; */

$sql_id = 'SELECT unas_id FROM `customer` WHERE email=:email';
$sql_order_item = 'SELECT * FROM `orders_items` WHERE order_id=:order_id';
$sql_customer = 'SELECT * FROM `customer` WHERE email = :cust_email';

foreach ($orders as $i => $row) {
    $sth = $conn->prepare($sql_id);
    $sth->bindParam(':email', $row['customer_email']);
    $sth->execute();
    $customer_id = $result = $sth->fetchColumn();

    $sth = $conn->prepare($sql_order_item);
    $sth->bindParam(':order_id', $row['order_id']);
    $sth->execute();
    $order_items = $result = $sth->fetchAll();
    $customerorder = $customerorders->addChild('Project');
    $customerorder->addAttribute('Id', $row['id']);
    $name = $customerorder->addChild('Name', $row['contact_name']);
    $categoryid = $customerorder->addChild('CategoryId', '18');
    $StatusId = $customerorder->addChild('StatusId', 'Regisztrált');
    $UserId = $customerorder->addChild('UserId', 'Király Attila');
    $Webshop_RegistrationDate = $customerorder->addChild('Webshop_RegistrationDate', $row['date']);
    $Webshop_Disabled = $customerorder->addChild('Webshop_Disabled');
    $Webshop_LostBasketContent = $customerorder->addChild('Webshop_LostBasketContent');
    $Webshop_LostBasketDate = $customerorder->addChild('Webshop_LostBasketDate');
    $Webshop_LostBasketValue = $customerorder->addChild('Webshop_LostBasketValue');
    $Webshop_AllLostBasket = $customerorder->addChild('Webshop_AllLostBasket');
    $business = $customerorder->addChild('Business');
    $b_name = $business->addChild('Name', $row['invoice_name']);
    $emails = $business->addChild('Emails');
    $email = $emails->addChild('Email');
    $value = $email->addChild('Value', $row['customer_email']);
    $contacts = $customerorder->addChild('Contacts');
    $contact = $contacts->addChild('Contact');
    $FirstName = $contact->addChild('FirstName', $row['contact_name']);
    $LastName = $contact->addChild('LastName');
    $c_emails = $contact->addChild('Emails');
    $c_email = $c_emails->addChild('Email');
    $c_value = $c_email->addChild('Value', $row['customer_email']);
    $orders = $customerorder->addChild('Orders');
    $order = $orders->addChild('Order');

    //itt ki kell cserélenm a rendelésszámból a - jelet 'semmire'

    $id_conv = array("-" => "");
    $id_conv_new = strtr($row['order_id'], $id_conv);

    $order->addAttribute('Id', $id_conv_new);

    $number = $order->addChild('Number', $row['order_id']);

    $currency = $order->addChild('CurrencyCode', 'HUF');
    $Performance = $order->addChild('Performance', $row['date']);
    $Subject = $order->addChild('Subject', '123');
    $Status = $order->addChild('Status', 'Issued');
    $customer = $order->addChild('Customer');
    $customer_name = $customer->addChild('Name', $row['contact_name']);
    $country_id = $customer->addChild('CountryId', $row['invoice_country']);
    $postal_code = $customer->addChild('PostalCode', $row['invoice_zip']);
    $city = $customer->addChild('City', $row['invoice_city']);
    $address = $customer->addChild('Address', $row['invoice_street']);
    $products = $order->addChild('Products');
    foreach ($order_items as $i2 => $order_item_row) {
        $product = $products->addChild('Product');

        // itt ki kell cserélni a shipping-cost-ot valamilyen számra

        $shipconv = array("shipping-cost" => "12345678", "discount-amount" => "87654321");
        $shipconv_new = strtr($order_item_row['Item_Sku'], $shipconv);


        $product->addAttribute('Id', $shipconv_new);
        $p_name = $product->addChild('Name', $order_item_row['Item_Name']);
        $p_sku = $product->addChild('SKU', $order_item_row['Item_Sku']);
        $p_ean = $product->addChild('EAN');
        $price_net = $product->addChild('PriceNet', $order_item_row['Item_PriceNet']);
        $price_qty = $product->addChild('Quantity', $order_item_row['Item_Quantity']);

        // itt kicseréljük a "db"-ot "darab"-ra

        $unit_conv = array("db" => "darab");
        $unit_conv_new = strtr($order_item_row['Item_Unit'], $unit_conv);

        $unit = $product->addChild('Unit', $unit_conv_new);
        $vat = $product->addChild('VAT', $order_item_row['Item_Vat']);
        $foldername = $product->addChild('FolderName', 'Alap termék');
    }
    file_put_contents('crmorders.xml', $customerorders->asXML());
}
echo $customerorders->asXML();

file_put_contents('crmorders.xml', $customerorders->asXML());

$link = mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db("wormsignh_mydb");

//Url összeállítása
$Url = 'https://27334:PpEV7zxvAk8sUHermCdWa5chMB4NOGZq@r3.minicrm.hu/Api/SyncFeed/27334?Source=http%3A%2F%2Fkereso.wormsign.hu%2Fupdate%2Fsymbol%2Fcrmorders.xml';

//Curl inicializálása
$Curl = curl_init();
curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);

//Url átadása a Curl-nek
curl_setopt($Curl, CURLOPT_URL, $Url);

//Curl kérés lefuttatása
$Response = curl_exec($Curl);

//Curl kérés futtatásában volt-e hiba?
if (curl_errno($Curl))
    $Error = "Hiba a Curl futtatásakor: " . curl_error($Curl);

//API által visszatérített http kód lekérése
$ResponseCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);
if ($ResponseCode != 200)
    $Error = "API Hibakód: {$ResponseCode} - Üzenet: {$Response}";

//Curl lezárása
curl_close($Curl);

//Válaszban kapott JSON dekódolása és kiíratása
$Response = json_decode($Response, true);
var_export($Response);


mysql_query("
UPDATE orders 
SET symbol_status='1'", $link);
