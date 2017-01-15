<?php

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
}

function get_customer_unas($client, $config, $config_db_my) {
    try {
        $auth = array(
            'Username' => $config['Username'],
            'PasswordCrypt' => $config['PasswordCrypt'],
            'ShopId' => $config['ShopId'],
            'AuthCode' => $config['AuthCode']
        );
        $params = array(
            'RegTimeStart' => strtotime('-1 day'), //2014.09.01.
            //'RegTimeStart' => "1414713600" //2014.10.31.
            'RegTimeEnd' => strtotime('2020-01-01')
        );
        $response = $client->getCustomer($auth, $params);
    } catch (SoapFault $error) {
        echo "<strong>getCustomer Error:</strong><br /> ";
        echo "<pre>" . print_r($error, true) . "</pre>";
    }

    try {
        $conn = new PDO($config_db_my['connection'], $config_db_my['username'], $config_db_my['password']);
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

    $sql = "INSERT IGNORE INTO wormsignh_atvetel.customer (downloaded,unas_id,email_address,webusername,"
            . " customer_name,zip,city,address,region,country,taxnumber,contact_phone,sms,"
            . "mailcountry,mailregion,mailzip,mailcity,mailstreet,contact_name)"
            . " VALUES (:downloaded,:unas_id,:email_address,:webusername,:customer_name,"
            . ":zip,:city,:address,:region,:country,:taxnumber,:contact_phone,"
            . ":sms,:mailcountry,:mailregion,:mailzip,:mailcity,:mailstreet,:contact_name)";

    $sql_sid = "UPDATE wormsignh_atvetel.customer SET"
            . " webusername=:webusername,unas_id=:unas_id WHERE email_address=:email_address";

    $sql_sid1 = "UPDATE wormsignh_atvetel.customer SET"
            . " code=:code, webusername=:webusername, WHERE customer_name=:customer_name AND address=:address";

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


//        $q = $conn->prepare($sql);
//        $q->execute(array(
//            ':unas_id' => $unas_id,
//            ':email_address' => $email,
//            ':webusername' => $webusername,
//            ':customer_name' => $name,
//            ':zip' => $zip,
//            ':city' => $city,
//            ':address' => $street,
//            ':region' => $region,
//            ':country' => $country,
//            ':taxnumber' => $taxnumber,
//            ':contact_phone' => $phone,
//            ':sms' => $sms,
//            ':mailcountry' => $mailcountry,
//            ':mailregion' => $mailregion,
//            ':mailzip' => $mailzip,
//            ':mailcity' => $mailcity,
//            ':mailstreet' => $mailstreet,
//            ':contact_name' => $contactname,
//            ':downloaded' => '0'
//        ));

        if (!trim($email)) {
            $q1 = $conn->prepare($sql_sid1);
            $q1->execute(array(
                ':unas_id' => $unas_id,
                ':customer_name' => $name,
                ':webusername' => $webusername,
                ':address' => $invoicestreet
            ));
        } else {
            $q1 = $conn->prepare($sql_sid);
            $q1->execute(array(
                ':unas_id' => $unas_id,
                ':webusername' => $webusername,
                ':email_address' => $email
            ));
        }
    }
    echo $customers->asXML();
}

get_customer_unas($client, $config['akkucentral'], $config_db_my);


//require_once 'update_pro_sku.php';

//echo $customers->asXML();