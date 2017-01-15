<?php


//egyelőre nem töltjük le a vevőket külön, csak 
//ami a rendelésben van, majd utólag letöltjük őket és az id-jüket beírjuk db-be az email cím egyezése alapján
//require_once '../customers/get_customers.php';

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
///////////////////////////////////////////////
// init

try {

    $conn = new PDO($config_db_my['connection'], $config_db_my['username'], $config_db_my['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

function get_orders_unas($client, $config, $config_db_my) {
    try {
        $auth = array(
            'Username' => $config['Username'],
            'PasswordCrypt' => $config['PasswordCrypt'],
            'ShopId' => $config['ShopId'],
            'AuthCode' => $config['AuthCode']
        );
//getOrder
        $params = array(
            'InvoiceStatus' => 1,
            'InvoiceAutoSet' => 1,
            'DateStart' => "-1 day",
            'DateEnd' => "2100.12.31"
        );
        $response = $client->getOrder($auth, $params);
    } catch (SoapFault $error) {
        echo "<strong>getOrder Error:</strong><br /> ";
        echo "<pre>" . print_r($error, true) . "</pre>";
    }

    //ezt majd ki kell rakni ha biztosan jó!
    try {
        $conn = new PDO($config_db_my['connection'], $config_db_my['username'], $config_db_my['password']);
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


    $sql_id = 'SELECT unas_id FROM wormsignh_atvetel.customer WHERE email_address=:email';


    $sql1 = " INSERT IGNORE INTO orders_items (order_id,Item_Id,Item_Sku,Item_Name,Item_Unit,Item_Quantity,Item_PriceNet,Item_PriceGross,Item_Vat)"
            . " VALUES (:order_id,:Item_Id,:Item_Sku,:Item_Name,:Item_Unit,:Item_Quantity,:Item_PriceNet,:Item_PriceGross,:Item_Vat)";

    $sql_customers = " INSERT IGNORE INTO wormsignh_atvetel.customer (unas_id,customer_name,country,region,zip,city,address,mailcountry,mailregion,mailzip,mailcity,mailstreet,email_address,contact_phone)"
            . " VALUES (:unas_id,:customer_name,:country,:region,:zip,:city,:address,:mailcountry,:mailregion,:mailzip,:mailcity,:mailstreet,:email_address,:contact_phone)";

    $sql_update = " UPDATE orders"
            . " SET status=:status"
            . " WHERE order_id=:order_id";

    foreach ($orders->Order as $sorszam => $order) {
        $order_id = $order->Key;
        $date = $order->Date;
        $customer_ids = $order->Customer->Id;
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
                ':customer_name' => $invoice_name,
                ':country' => $invoice_country,
                ':region' => $invoice_county,
                ':zip' => $invoice_zip,
                ':city' => $invoice_city,
                ':address' => $invoice_street,
                ':mailcountry' => $shipping_country,
                ':mailregion' => $shipping_county,
                ':mailzip' => $shipping_zip,
                ':mailcity' => $shipping_city,
                ':mailstreet' => $shipping_street,
                ':email_address' => $customer_email,
                ':contact_phone' => $contact_phone,
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
    foreach ($orders->Order as $sorszam => $order) {
        $order_id1 = $order->Key;
        $status = $order->Status;
        $q1 = $conn->prepare($sql_update);
        $q1->execute(array(
            ':order_id' => $order_id1,
            ':status' => $status,
        ));
    }
    echo $orders->asXML();
}

get_orders_unas($client, $config['akkucentral'], $config_db_my);


//itt beirjuk a cameron_skut az orders_itesmbe az unasból letöltött cikkszám egyezése alapján

$sku = " UPDATE wormsignh_mydb.orders_items oi
INNER JOIN wormsignh_update.full_stock wx ON(oi.Item_sku = wx.xrefid)
SET oi.symbol_id=wx.productcode ";


//itt átirjuk a speciális termékek cikkszámát a megfelelőre
$spec = " UPDATE wormsignh_mydb.orders_items
set symbol_id='shipping-cost'
WHERE Item_Id='shipping-cost' ";


$spec1 = " UPDATE wormsignh_mydb.orders_items
set symbol_id='discount-amount'
WHERE Item_Id='discount-amount' ";

$spec2 = " UPDATE wormsignh_mydb.orders_items
set symbol_id='discount-percent'
WHERE Item_Id='discount-percent' ";

try {
    $sth = $conn->prepare($sku);
    $statement = $sth->execute();

    $sth = $conn->prepare($spec);
    $statement = $sth->execute();

    $sth = $conn->prepare($spec1);
    $statement = $sth->execute();

    $sth = $conn->prepare($spec2);
    $statement = $sth->execute();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}


