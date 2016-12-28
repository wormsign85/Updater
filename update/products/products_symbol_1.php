<?php

$user = 'wormsignh_worm';
$pass = 'IxOn1985';

try {
    $conn = new PDO('mysql:host=localhost;dbname=wormsignh_mydb', $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
//file_put_contents('products.xml', $_POST['xmldata']);
//exit;
//$xml = file_get_contents('products.xml');
//$xml = $_POST('xmldata');

$filename = 'products_' . md5(uniqid(true) . rand(1, 999999)) . '.xml';
file_put_contents($filename, $_POST['xmldata']);
$xml = file_get_contents($filename);

//$xml = file_get_contents('products_02e172e24a524dd9964ffee89bacfeb5.xml');


$products = new SimpleXMLElement($xml);


$sqlutf = "set names 'utf8'";
$sth = $conn->prepare($sqlutf);
$statement = $sth->execute();

$sql = "REPLACE INTO products SET id=:id,code=:code,suppliercode=:suppliercode,name=:name,active=:active,"
    . " webname=:webname,ProductWebGroup=:ProductWebGroup,productworldcodes=:productworldcodes";

$sql1 = "REPLACE INTO product_atrributes SET name=:name,value=:value,filter=:filter,code=:code";

try {
    foreach ($products->Product as $sorszam => $product) {
        $id = $product->id;
        $code = $product->code;
        $suppliercode = $product->suppliercode;
        $name = $product->name;
        $active = $product->active;
        $webname = $product->webname;
        // Ha nincs megadva ProductWebGroup, most 0-ra állítom
        $webgroup = $product->ProductWebGroups->ProductWebGroup ? $product->ProductWebGroups->ProductWebGroup : 0;
        $productworldcodes = $product->productworldcodes->productworldcode->code;
        $q = $conn->prepare($sql);
        $q->execute(array(
            ':id' => $id,
            ':code' => $code,
            ':suppliercode' => $suppliercode,
            ':name' => $name,
            ':active' => $active,
            ':webname' => $webname,
            ':ProductWebGroup' => $webgroup,
            ':productworldcodes' => $productworldcodes
        ));
    }
    if ($product->productattributes->productattribute)
    foreach ($products->Product as $sorszam => $product) {
        $code = $product->code;
        foreach ($product->productattributes->productattribute as $sorszam => $attribute) {
            $attname = $attribute->name;
            $attvalue = $attribute->value;
            $attfilter = $attribute->filter;
            $q = $conn->prepare($sql1);
            $q->execute(array(
                ':code' => $code,
                ':name' => $attname,
                ':value' => $attvalue,
                ':filter' => $attfilter,
            ));
        }
    }
    // Sikeres futás logolása
    $logline = date('Y-m-d H:i:s') . ' OK:  (' . $filename . ")\n";
    file_put_contents('products_symbol_1.log', $logline, FILE_APPEND);
    
    // Nincs hiba
    echo 'OK';
} catch (Exception $e) {
    // Kritikus hiba logolása
    $logline = date('Y-m-d H:i:s') . '(' . $filename . ')' . ' Exception: ' . strtr($e->getMessage(), array("\r" => ' ', "\n" => ' ')) . "\n";
    file_put_contents('products_symbol_1.log', $logline, FILE_APPEND);
}
