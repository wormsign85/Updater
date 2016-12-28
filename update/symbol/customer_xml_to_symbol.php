<?php
//header('content-type: text/xml');
//require_once ('get_orders.php');

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

    require_once 'get_orders.php';

    require_once 'get_customers.php';
}


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

$sql = 'SELECT * FROM wormsignh_mydb.customer WHERE downloaded=0';
$sth = $conn->prepare($sql);
$statement = $sth->execute();
$customers = $sth->fetchAll();

$vasarlok = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Customers/>');

foreach ($customers as $i => $row) {
    $customer = $vasarlok->addChild('Customer');
    $feedbackurl = $customer->addChild('feedbackurl', 'http://kereso.wormsign.hu/update/symbol/customer_feedbackurl.php?id='
            . $row['id'] . '&amp;text=');
    $errorurl = $customer->addChild('errorurl', 'http://kereso.wormsign.hu/update/symbol/customer_error.php?errorid='
            . $row['id'] . '&amp;text=');
    $id = $customer->addChild('id',$row['unas_id']);
    $sid = $customer->addChild('sid',$row['sid']);
    $code = $customer->addChild('code',$row['code']);
    $customer->name = $row['name'];
    $country = $customer->addChild('country',$row['country']);
    $region = $customer->addChild('region',$row['region']);
    $zip = $customer->addChild('zip',$row['zip']);
    $city = $customer->addChild('city',$row['city']);
    $street = $customer->addChild('street',$row['street']);
    $housenumber = $customer->addChild('housenumber');
    $mailcountry = $customer->addChild('mailcountry',$row['mailcountry']);
    $mailregion = $customer->addChild('mailregion',$row['mailregion']);
    $mailzip = $customer->addChild('mailzip',$row['mailzip']);
    $mailcity = $customer->addChild('mailcity',$row['mailcity']);
    $mailstreet = $customer->addChild('mailstreet',$row['mailstreet']);
    $mailhousenumber = $customer->addChild('mailhousenumber');
    $taxnumber = $customer->addChild('taxnumber',$row['taxnumber']);
    $bankaccount = $customer->addChild('bankaccount');
    $contactname = $customer->addChild('contactname',$row['contactname']);
    $email = $customer->addChild('email',$row['email']);
    $phone = $customer->addChild('phone',$row['phone']);
    $sms = $customer->addChild('sms',$row['sms']);
    $fax = $customer->addChild('fax');
    $description = $customer->addChild('description');
    $customercategory = $customer->addChild('customercategory');
    $pricecategoryname = $customer->addChild('pricecategoryname');
    $webusername = $customer->addChild('webusername',$row['webusername']);
    $webpassword = $customer->addChild('webpassword');
    $strexa = $customer->addChild('strexa');
    $strexb = $customer->addChild('strexb');
    $strexc = $customer->addChild('strexc');
    $strexd = $customer->addChild('strexd');
    $dateexa = $customer->addChild('dateexa');
    $dateexb = $customer->addChild('dateexb');
    $numexa = $customer->addChild('numexa');
    $numexb = $customer->addChild('numexb');
    $numexc = $customer->addChild('numexc');
    $boolexa = $customer->addChild('boolexa');
    $boolexb = $customer->addChild('boolexb');
    $lookupexa = $customer->addChild('lookupexa');
    $lookupexb = $customer->addChild('lookupexb');
    $lookupexc = $customer->addChild('lookupexc');
    $lookupexd = $customer->addChild('lookupexd');
}

//Ez az átvételi oldalból jött vevők listája


$sql = 'SELECT * FROM wormsignh_atvetel.customer WHERE downloaded=0';
$sth = $conn->prepare($sql);
$statement = $sth->execute();
$customers = $sth->fetchAll();

foreach ($customers as $i => $row) {
    $customer = $vasarlok->addChild('Customer');
    $feedbackurl = $customer->addChild('feedbackurl', 'http://kereso.wormsign.hu/update/symbol/customer_feedbackurl.php?id='
            . $row['id'] . '&amp;text=');
    $errorurl = $customer->addChild('errorurl', 'http://kereso.wormsign.hu/update/symbol/customer_error.php?errorid='
            . $row['id'] . '&amp;text=');
    $id = $customer->addChild('id',$row['id']);
    $sid = $customer->addChild('sid',$row['sid']);
    $code = $customer->addChild('code',$row['code']);
    $customer->name = $row['customer_name'];
    $country = $customer->addChild('country',$row['country']);
    $region = $customer->addChild('region',$row['region']);
    $zip = $customer->addChild('zip',$row['zip']);
    $city = $customer->addChild('city',$row['city']);
    $street = $customer->addChild('street',$row['address']);
    $housenumber = $customer->addChild('housenumber');
    $mailcountry = $customer->addChild('mailcountry',$row['mailcountry']);
    $mailregion = $customer->addChild('mailregion',$row['mailregion']);
    $mailzip = $customer->addChild('mailzip',$row['mailzip']);
    $mailcity = $customer->addChild('mailcity',$row['mailcity']);
    $mailstreet = $customer->addChild('mailstreet',$row['mailstreet']);
    $mailhousenumber = $customer->addChild('mailhousenumber');
    $taxnumber = $customer->addChild('taxnumber',$row['taxnumber']);
    $bankaccount = $customer->addChild('bankaccount');
    $contactname = $customer->addChild('contactname',$row['contact_name']);
    $email = $customer->addChild('email',$row['email_address']);
    $phone = $customer->addChild('phone',$row['contact_phone']);
    $sms = $customer->addChild('sms',$row['sms']);
    $fax = $customer->addChild('fax');
    $description = $customer->addChild('description');
    $customercategory = $customer->addChild('customercategory');
    $pricecategoryname = $customer->addChild('pricecategoryname');
    $webusername = $customer->addChild('webusername',$row['webusername']);
    $webpassword = $customer->addChild('webpassword');
    $strexa = $customer->addChild('strexa');
    $strexb = $customer->addChild('strexb');
    $strexc = $customer->addChild('strexc');
    $strexd = $customer->addChild('strexd');
    $dateexa = $customer->addChild('dateexa');
    $dateexb = $customer->addChild('dateexb');
    $numexa = $customer->addChild('numexa');
    $numexb = $customer->addChild('numexb');
    $numexc = $customer->addChild('numexc');
    $boolexa = $customer->addChild('boolexa');
    $boolexb = $customer->addChild('boolexb');
    $lookupexa = $customer->addChild('lookupexa');
    $lookupexb = $customer->addChild('lookupexb');
    $lookupexc = $customer->addChild('lookupexc');
    $lookupexd = $customer->addChild('lookupexd');
}

echo $vasarlok->asXML();

file_put_contents('customers.xml', $vasarlok->asXML());

$result = ata_mysql_query("UPDATE wormsignh_mydb.customer SET downloaded='1'
       ");

$result = ata_mysql_query("UPDATE wormsignh_atvetel.customer SET downloaded='1'
       ");


