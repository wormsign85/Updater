<?php

$initfile = __DIR__ . '../../ujverzio/haffner/lib/init.php';
if (file_exists($initfile)) {
    // lokális
    require_once $initfile;

    $user = 'root';
    $pass = '';
} else {
    // éles
    require_once __DIR__ . '/../symbol/lib/init.php';

    $user = 'wormsignh_worm';
    $pass = 'IxOn1985';
}

$user = 'wormsignh_worm';
$pass = 'IxOn1985';

//$user = 'root';
//$pass = '';

try {
    $conn = new PDO('mysql:host=localhost;dbname=wormsignh_mydb', $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$filename = 'products_' . md5(uniqid(true) . rand(1, 999999)) . '.xml';
file_put_contents($filename, $_POST['xmldata']);
$xml = file_get_contents($filename);

//foreach (glob("*.xml") as $filename) {
//    echo "$filename size " . filesize($filename) . "\n";
//    $xml = file_get_contents($filename);
//    if (!$xml)
//        continue;

    $products = new SimpleXMLElement($xml);

    $sqlutf = "set names 'utf8'";
    $sth = $conn->prepare($sqlutf);
    $statement = $sth->execute();

    $sql = " REPLACE INTO products SET id=:id,code=:code,suppliercode=:suppliercode,name=:name,active=:active,"
            . " webname=:webname,ProductWebGroup=:ProductWebGroup";

    $sql1 = " REPLACE INTO product_attributes SET name=:name,value=:value,filter=:filter,code=:code";

    $sql2 = " UPDATE wormsignh_kapacitas.cameron_news SET arlistara=:arlistara "
            . " WHERE szla_id = :sku";

    $sql_ean = " UPDATE wormsignh_kapacitas.cameron_news SET rp_ean=:rp_ean "
            . " WHERE szla_id = :sku";

    foreach ($products->Product as $sorszam => $product) {
        $id = $product->id;
        $code = $product->code;
        $barcode = $product->barcode;
        $suppliercode = $product->suppliercode;
        $name = $product->name;
        $active = $product->active;
        $webname = $product->webname;
        $webgroup = $product->ProductWebGroups->ProductWebGroup;
        //$productworldcodes = $product->productworldcodes->productworldcode->code;
        $q = $conn->prepare($sql);
        $q->execute(array(
            ':id' => $id,
            ':code' => $code,
            ':suppliercode' => $suppliercode,
            ':name' => $name,
            ':active' => $active,
            ':webname' => $webname,
            ':ProductWebGroup' => $webgroup,
                //':productworldcodes' => $productworldcodes
        ));


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

        if ($attname == 'RealPower Nagyker') {
            foreach ($product->productattributes->productattribute as $sorszam => $attribute) {
                $attname = $attribute->name;
                $attvalue = $attribute->value;
//                $attfilter = $attribute->filter;

                $q = $conn->prepare($sql2);
                $q->execute(array(
                    ':sku' => $code,
                    ':arlistara' => $attvalue
                ));
            }
        }
        $q = $conn->prepare($sql_ean);
        $q->execute(array(
            ':sku' => $code,
            ':rp_ean' => $barcode
                //':productworldcodes' => $productworldcodes
        ));
    }
//}

$result = ata_mysql_query("
  INSERT IGNORE INTO wormsignh_atvetel.felujitas_symbol
  (kod, nev, letrehozva)
  SELECT code, name, NOW() FROM wormsignh_mydb.products
  ");

$result = ata_mysql_query("
UPDATE wormsignh_atvetel.felujitas_symbol
SET gyartando ='1' WHERE nev LIKE '%felúj%' OR nev LIKE '%pakk%'
  ");

$result = ata_mysql_query("
UPDATE wormsignh_atvetel.battery pp
INNER JOIN wormsignh_atvetel.felujitas_symbol w ON(pp.capacity=w.kod)
SET pp.gyartando =w.gyartando
  ");

//$result = ata_mysql_query("UPDATE wormsignh_atvetel.felujitas_symbol pp
//  INNER JOIN wormsignh_mydb.products w ON (pp.kod = w.code)
//  SET pp.code=w.code
//  ");


echo 'OK';
