<?php

//header('content-type: text/xml');
//require_once ('get_orders.php');
require_once __DIR__ . '/lib/init.php';

try {
    $conn = new PDO($config_db_atvet['connection'], $config_db_atvet['username'], $config_db_atvet['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$sql1 = "set names 'utf8'";
$sth = $conn->prepare($sql1);
$statement = $sth->execute();

//$sql_utf = "ALTER TABLE wormsignh_mydb.symbol_customers CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
//$sth = $conn->prepare($sql_utf);
//$statement = $sth->execute();


$sql = 'INSERT IGNORE INTO wormsignh_atvetel.customer SET sid=:sid , code=:code , customer_name=:customer_name,'
        . 'customercategory = :customercategory,country = :country,'
        . 'region = :region,zip = :zip,city = :city,address = :address,mailzip = :mailzip,'
        . 'mailcity = :mailcity,mailstreet = :mailstreet , mailhousenumber = :mailhousenumber,paymentmethod=:paymentmethod'
        . ',pricecategory = :pricecategory,pricecategoryname = :pricecategoryname,'
        . 'discountpercent = :discountpercent,transportmode = :transportmode,'
        . 'taxnumber = :taxnumber,contact_name = :contact_name,'
        . 'email_address = :email_address,contact_phone = :contact_phone,sms=:sms, letrehozva = NOW()';

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
//    $feedbackurl = $customer->addChild('feedbackurl', 'http://kereso.wormsign.hu/update/symbol/customer_feedbackurl.php?id='
//            . $row['id'] . '&amp;text=');
//    $errorurl = $customer->addChild('errorurl', 'http://kereso.wormsign.hu/update/symbol/customer_error.php?errorid='
//            . $row['id'] . '&amp;text=');

        $id = $customer->id;
//    $sid = $customer->addChild('sid',$row['sid']);
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
//}

//    $result = ata_mysql_query("UPDATE wormsignh_mydb.customer pp
//  INNER JOIN wormsignh_atvetel.customer w ON (pp.email = w.email_address)
//  SET pp.code=w.code
//  ");






//$result = ata_mysql_query("UPDATE wormsignh_atvetel.customer pp
//  INNER JOIN wormsignh_mydb.symbol_customers w ON (w.email = pp.email)
//  SET pp.sid=w.id, pp.code=w.code
//  ");
//
//echo 'Készlet másolása xref-be <br/>';


//$downloaded = "UPDATE customer SET downloaded='1'";

//$sth = $conn->prepare($downloaded);
//$statement = $sth->execute();

//DELETE from customer WHERE id NOT IN(237492,
//605742,
//519,
//635,
//636,
//519,
//165,
//1157,
//178,
//2437,
//5123,
//1518,
//3388,
//7738,
//7739,
//5124,
//4582,
//5125,
//7740,
//5126,
//5127,
//7744,
//7742,
//7743,
//7741,
//10355,
//7739,
//9057,
//7743,
//10356,
//9101,
//10361,
//9081,
//9059,
//9127,
//9659,
//9068,
//9108,
//9085,
//10361,
//7918,
//10236,
//10362,
//10363,
//10364,
//9708,
//10365,
//10366,
//10361,
//10367,
//7741,
//10368,
//10369,
//10370,
//10371
//)

