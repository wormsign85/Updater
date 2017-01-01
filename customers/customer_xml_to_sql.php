<?php

//header('content-type: text/xml');
//require_once ('get_orders.php');
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

try {
    $conn = new PDO($config_db_stock['connection'], $config_db_stock['username'], $config_db_stock['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$sql1 = "set names 'utf8'";
$sth = $conn->prepare($sql1);
$statement = $sth->execute();


$sql = 'INSERT IGNORE INTO wormsignh_atvetel.customer SET sid=:sid , code=:code , customer_name=:customer_name,'
        . 'customercategory = :customercategory,country = :country,'
        . 'region = :region,zip = :zip,city = :city,address = :address,mailzip = :mailzip,'
        . 'mailcity = :mailcity,mailstreet = :mailstreet , mailhousenumber = :mailhousenumber,paymentmethod=:paymentmethod'
        . ',pricecategory = :pricecategory,pricecategoryname = :pricecategoryname,'
        . 'discountpercent = :discountpercent,transportmode = :transportmode,'
        . 'taxnumber = :taxnumber,contact_name = :contact_name,'
        . 'email_address = :email_address,contact_phone = :contact_phone,sms=:sms, letrehozva = NOW()';

//ha letöltöttük a vevő symbolba, visszatöltjük a symbol azonosító kódját, 
//hogy legközelebb már ne új vevőként jöjjön le, ez az átvételi oldalra vonatkozik

$sql_sid = "UPDATE wormsignh_atvetel.customer SET"
        . " code=:code WHERE email_address=:email_address";

$sql_sid1 = "UPDATE wormsignh_atvetel.customer SET"
        . " code=:code WHERE customer_name=:customer_name AND address=:address";

$filename = 'customers_' . md5(uniqid(true) . rand(1, 999999)) . '.xml';
file_put_contents($filename, $_POST['xmldata']);
$xml = file_get_contents($filename);

//foreach (glob("*.xml") as $filename) {
//    echo "$filename size " . filesize($filename) . "\n";
//    $xml = file_get_contents($filename);
//    if (!$xml)
//        continue;
//    $xml = file_get_contents($filename);

$CustomersUp = new SimpleXMLElement($xml);



foreach ($CustomersUp->Customer as $cust => $customer) {
    $feedbackurl = $customer->addChild('feedbackurl', 'http://update.wormsign.hu/customers/customer_feedbackurl.php?id='
            . $row['id'] . '&amp;text=');
    $errorurl = $customer->addChild('errorurl', 'http://update.wormsign.hu/customers/customer_error.php?errorid='
            . $row['id'] . '&amp;text=');

    $id = $customer->id;
    $sid = $customer->sid;
    $code = $customer->code;
    $name = $customer->name;
    $customercategory = $customer->customercategory;
    $invoicecountry = $customer->invoicecountry;
    $invoiceregion = $customer->invoiceregion;
    $invoicezip = $customer->invoicezip;
    $invoicecity = $customer->invoicecity;
    $invoicestreet = $customer->invoicestreet;
//    $invoicehousenumber = $customer->invoicehousenumber;
//    $mailcountry = $customer->mailcountry;
//    $mailregion = $customer->mailregion;
    $mailzip = $customer->mailzip;
    $mailcity = $customer->mailcity;
    $mailstreet = $customer->mailstreet;
    $mailhousenumber = $customer->mailhousenumber;
    $paymentmethod = $customer->paymentmethod;
    $pricecategory = $customer->pricecategory;
    $pricecategoryname = $customer->pricecategoryname;
    $discountpercent = $customer->discountpercent;
    $transportmode = $customer->transportmode;
    $taxnumber = $customer->taxnumber;
    $contactname = $customer->contactname;
    $email = $customer->email;
    $phone = $customer->phone;
    $sms = $customer->sms;

    $q = $conn->prepare($sql);
    $q->execute(array(
        ':sid' => $id,
        ':code' => $code,
        ':customer_name' => $name,
        ':customercategory' => $customercategory,
        ':country' => $invoicecountry,
        ':region' => $invoiceregion,
        ':zip' => $invoicezip,
        ':city' => $invoicecity,
        ':address' => $invoicestreet,
//            ':invoicehousenumber' => $invoicehousenumber,
//            ':mailcountry' => $mailcountry,
//            ':mailregion' => $mailregion,
        ':mailzip' => $mailzip,
        ':mailcity' => $mailcity,
        ':mailstreet' => $mailstreet,
        ':mailhousenumber' => $mailhousenumber,
        ':paymentmethod' => $paymentmethod,
        ':pricecategory' => $pricecategory,
        ':pricecategoryname' => $pricecategoryname,
        ':discountpercent' => $discountpercent,
        ':transportmode' => $transportmode,
        ':taxnumber' => $taxnumber,
        ':contact_name' => $contactname,
        ':email_address' => $email,
        ':contact_phone' => $phone,
        ':sms' => $sms
    ));

    if (!trim($email)) {
        $q1 = $conn->prepare($sql_sid1);
        $q1->execute(array(
            ':code' => $code,
            ':customer_name' => $name,
            ':address' => $invoicestreet
        ));
    } else {

        $q1 = $conn->prepare($sql_sid);
        $q1->execute(array(
            ':code' => $code,
            ':email_address' => $email
        ));
    }
}
