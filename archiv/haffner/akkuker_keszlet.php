<?php

set_time_limit(600);

require_once 'lib/init.php';

///////////////////////////////////////////////
// init


try {
    $conn = new PDO($config_db_ak['connection'], $config_db_ak['username'], $config_db_ak['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

ata_mysql_query("set names 'utf8'", $connection);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://akkuker.com/index.php?route=feed/olcsobbat&key=akkuker");
//curl_setopt($ch, CURLOPT_URL, "www.debranet.com/xml/xml.php?from=0&to=5000&onstock=1");;
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // Videos are needed to transfered in binary
$xml = curl_exec($ch); // $result will have your video.
curl_close($ch);

//$xml1 = file_put_contents('akkuker.xml', $xml);

$products = new SimpleXMLElement($xml);


print_r($xml);


$sqlutf = "set names 'utf8'";
$sth = $conn->prepare($sqlutf);
$statement = $sth->execute();

$sql = " UPDATE wormsignh_wormsign_hu.tps_webshop"
  . " SET keszlet_ak=:keszlet_ak WHERE ak_id=:ak_id";


foreach ($products->product as $sorszam => $product) {
    $ak_id = $product->id;
    $stock = $product->stock;

    $q = $conn->prepare($sql);
    $q->execute(array(
        ':ak_id' => $ak_id,
        ':keszlet_ak' => $stock
//        ':keszlet_ak' => $stock ? 1 : 0
            )
    );
}

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
    SET keszlet_new=if(keszlet_ak='true','1',0),
    keszlet=if(keszlet_ak='true','1',0) where ak_id!=''
       ");

require_once 'stock_upload_akkucentral.php';