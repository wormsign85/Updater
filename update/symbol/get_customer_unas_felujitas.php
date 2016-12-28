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
    'ShopId' => '91525',
    'AuthCode' => '313cfaa213'
);
///////////////////////////////////////////////
// connect
$client = new SoapClient($soap_server);

///////////////////////////////////////////////
//getCustomer
try {
    $params = array(
        'RegTimeStart' => strtotime('2014-11-01'), //2014.09.01.
        //'RegTimeStart' => "1414713600" //2014.10.31.
        'RegTimeEnd' => strtotime('2020-01-01')
    );
    $response = $client->getCustomer($auth, $params);
} catch (SoapFault $error) {
    echo "<strong>getCustomer Error:</strong><br /> ";
    echo "<pre>" . print_r($error, true) . "</pre>";
}

$user = 'wormsignh_worm';
$pass = 'IxOn1985';

try {
    $conn = new PDO('mysql:host=localhost;dbname=wormsignh_mydb', $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
file_put_contents('customers.xml', $response);

$customers = new SimpleXMLElement($response);

$sqlutf = "set names 'utf8'";
$sth = $conn->prepare($sqlutf);
$statement = $sth->execute();


$sql = "INSERT IGNORE INTO customer (downloaded,unas_id,email,webusername,"
        . " name,zip,city,street,region,country,taxnumber,phone,sms,mailcountry,mailregion,mailzip,mailcity,mailstreet,contactname)"
        . " VALUES (:downloaded,:unas_id,:email,:webusername,:name,:zip,:city,:street,:region,:country,:taxnumber,:phone,:sms,:mailcountry,:mailregion,:mailzip,:mailcity,:mailstreet,:contactname)";


/* $sql = "INSERT IGNORE INTO customer (id,name,country,region,zip,city,street,mailcountry,"
  . " mailregion,mailzip,mailcity,mailstreet,taxnumber,contactname,email,phone,sms,webusername)"
  . " VALUES (:id,:name,:country,:region,:zip,:city,:street,:mailcountry,"
  . " :mailregion,:mailzip,:mailcity,:mailstreet,:taxnumber,:contactname,:email,:phone,:sms,:webusername)";

  $sql1 = " INSERT IGNORE INTO orders_items (order_id,Item_Id,Item_Sku,Item_Name,Item_Unit,Item_Quantity,Item_PriceNet,Item_PriceGross,Item_Vat)"
  . " VALUES (:order_id,:Item_Id,:Item_Sku,:Item_Name,:Item_Unit,:Item_Quantity,:Item_PriceNet,:Item_PriceGross,:Item_Vat)";
 */

foreach ($customers->Customer as $cust => $customer) {
    $unas_id = $customer->Id;
    $email = $customer->Email;
    $webusername = $customer->Username;
    $name = $customer->Addresses->Invoice->Name;
    $zip = $customer->Addresses->Invoice->ZIP;
    $city = $customer->Addresses->Invoice->City;
    $street = $customer->Addresses->Invoice->Street;
    $region = $customer->Addresses->Invoice->County;
    $country = $customer->Addresses->Invoice->Country;
    $taxnumber = $customer->Addresses->Invoice->Taxnumber;
    $phone = $customer->Contact->Phone;
    $sms = $customer->Contact->Mobile;
    $mailcountry = $customer->Addresses->Shipping->Country;
    $mailregion = $customer->Addresses->Shipping->County;
    $mailzip = $customer->Addresses->Shipping->ZIP;
    $mailcity = $customer->Addresses->Shipping->City;
    $mailstreet = $customer->Addresses->Shipping->Street;
    $contactname = $customer->Addresses->Contact->Name;

    $q = $conn->prepare($sql);
    $q->execute(array(
        ':unas_id' => $unas_id,
        ':email' => $email,
        ':webusername' => $webusername,
        ':name' => $name,
        ':zip' => $zip,
        ':city' => $city,
        ':street' => $street,
        ':region' => $region,
        ':country' => $country,
        ':taxnumber' => $taxnumber,
        ':phone' => $phone,
        ':sms' => $sms,
        ':mailcountry' => $mailcountry,
        ':mailregion' => $mailregion,
        ':mailzip' => $mailzip,
        ':mailcity' => $mailcity,
        ':mailstreet' => $mailstreet,
        ':contactname' => $contactname,
        ':downloaded' => '0'
    ));
}