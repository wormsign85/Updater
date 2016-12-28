<?php

ini_set('max_execution_time', 300);
$user = 'wormsignh_worm';
$pass = 'IxOn1985';

try {
    $conn = new PDO('mysql:host=localhost;dbname=wormsignh_atvetel', $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$filename = 'prices_' . md5(uniqid(true) . rand(1, 999999)) . '.xml';
file_put_contents($filename, $_POST['xmldata']);
    $xml = file_get_contents($filename);


//foreach (glob("*.xml") as $filename) {
//    echo "$filename size " . filesize($filename) . "\n";
//    $xml = file_get_contents($filename);
//    if (!$xml)
//        continue;


    $prices = new SimpleXMLElement($xml);

    $sqlutf = "set names 'utf8'";
    $sth = $conn->prepare($sqlutf);
    $statement = $sth->execute();

    $sql = "REPLACE INTO prices SET product=:product,productcode=:productcode,"
            . "pricecategory=:pricecategory,pricecategoryName=:pricecategoryName,priceCurrency=:priceCurrency,"
            . " value=:value";

    foreach ($prices->ProductPrice as $sorszam => $price) {
        $product = $price->product;
        $productcode = $price->productcode;
        foreach ($price->price as $sorszam => $price1) {
            $pricecategory = $price1->pricecategory;
            $pricecategoryName = $price1->pricecategoryName;
            $priceCurrency = $price1->priceCurrency;
            $value = $price1->value;
            $q = $conn->prepare($sql);
            $q->execute(array(
                ':product' => $product,
                ':productcode' => $productcode,
                ':pricecategory' => $pricecategory,
                ':pricecategoryName' => $pricecategoryName,
                ':priceCurrency' => $priceCurrency,
                ':value' => $value,
            ));
        }
    }
//}


echo 'OK';

//require_once 'update_prices.php';

