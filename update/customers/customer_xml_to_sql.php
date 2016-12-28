<?php
//header('content-type: text/xml');
//require_once ('get_orders.php');
require_once 'lib/init.php';

    try {
        $conn = new PDO($config_db_my['connection'], $config_db_my['username'], $config_db_my['password']);
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


$sql = 'INSERT IGNORE INTO wormsignh_mydb.symbol_customers (id,code,name,customercategory,invoicecountry,'
        . 'invoiceregion,invoicezip,invoicecity,invoicestreet,invoicehousenumber,mailcountry,mailregion,mailzip,'
        . 'mailcity,mailstreet,mailhousenumber,paymentmethod,pricecategory,pricecategoryname,'
        . 'discountpercent,transportmode,'
        . 'taxnumber,contactname,email,phone,sms)'
        
        . 'VALUES (:id,:code,:name,:customercategory,:invoicecountry,:invoiceregion,'
        . ':invoicezip,:invoicecity,:invoicestreet,:invoicehousenumber,:mailcountry,:mailregion,:mailzip,'
        . ':mailcity,:mailstreet,:mailhousenumber,:paymentmethod,:pricecategory,'
        . ':pricecategoryname,:discountpercent,:transportmode,'
        . ':taxnumber,:contactname,:email,:phone,:sms)';

$filename = 'customers_' . md5(uniqid(true) . rand(1, 999999)) . '.xml';
file_put_contents($filename, $_POST['xmldata']);
$xml = file_get_contents($filename);

//file_put_contents('stock.xml', $_POST['xmldata']);

//exit;
//$xml = file_get_contents('customersup.xml');
//$xml = $_POST('xmldata');

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
    $invoicehousenumber = $customer->invoicehousenumber;
    $mailcountry = $customer->mailcountry;
    $mailregion = $customer->mailregion;
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
            ':id' => $id,
            ':code' => $code,
            ':name' => $name,
            ':customercategory' => $customercategory,
            ':invoicecountry' => $invoicecountry,
            ':invoiceregion' => $invoiceregion,
            ':invoicezip' => $invoicezip,
            ':invoicecity' => $invoicecity,
            ':invoicestreet' => $invoicestreet,
            ':invoicehousenumber' => $invoicehousenumber,
            ':mailcountry' => $mailcountry,
            ':mailregion' => $mailregion,
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
            ':contactname' => $contactname,
            ':email' => $email,
            ':phone' => $phone,
            ':sms' => $sms
            ));
}





//$downloaded = "UPDATE customer SET downloaded='1'";

//$sth = $conn->prepare($downloaded);
//$statement = $sth->execute();