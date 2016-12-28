<?php
set_time_limit(180);
$user = 'wormsignh_worm';
$pass = 'IxOn1985';

try {
    $conn = new PDO('mysql:host=localhost;dbname=wormsignh_debranet', $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://178.62.0.217/debrabasz.php");
//curl_setopt($ch, CURLOPT_URL, "www.debranet.com/xml/xml.php?from=0&to=5000&onstock=1");;
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false); 
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // Videos are needed to transfered in binary 
$xml = curl_exec($ch); // $result will have your video.
curl_close($ch);
$products = new SimpleXMLElement($xml);

print_r($xml);

//echo $products->asXML();

$sqlutf = "set names 'utf8'";
$sth = $conn->prepare($sqlutf);
$statement = $sth->execute();

$sql = "INSERT IGNORE INTO fehernemuk (product_id,product_code,product_ean,product_active,product_pricecategory,"
        . "product_maingroup,product_subgroup,product_minimum,product_promo,product_stock,product_arrivaldate,"
        . "product_brandid,product_video,product_note,product_export,product_export1,product_fixedprice,product_toend,"
        . "product_dadd,product_dmod,product_price_1_eur,product_price_1_huf,product_price_1_ron,product_price_retail_huf,"
        . "product_price_retail_eur,product_price_retail_ron,product_pic1,product_pic2,product_pic3,product_marks,"
        . "product_name_language,product_name_data,product_descriptions)"
        . "VALUES (:product_id,:product_code,:product_ean,:product_active,:product_pricecategory,"
        . ":product_maingroup,:product_subgroup,:product_minimum,:product_promo,:product_stock,:product_arrivaldate,"
        . ":product_brandid,:product_video,:product_note,:product_export,:product_export1,:product_fixedprice,:product_toend,"
        . ":product_dadd,:product_dmod,:product_price_1_eur,:product_price_1_huf,:product_price_1_ron,:product_price_retail_huf,"
        . ":product_price_retail_eur,:product_price_retail_ron,:product_pic1,:product_pic2,:product_pic3,:product_marks,"
        . ":product_name_language,:product_name_data,:product_descriptions)";

$sql_prop = "INSERT IGNORE INTO prod_property (product_id,product_code,product_property)"
        . "VALUES (:product_id,:product_code,:product_property)";

foreach ($products->product as $prod => $product) {
    $product_id = $product->product_id;
    $product_code = $product->product_code;
    $product_ean = $product->product_ean;
    $product_active = $product->product_active;
    $product_pricecategory = $product->product_pricecategory;
    $product_maingroup = $product->product_maingroup;
    $product_subgroup = $product->product_subgroup;
    $product_minimum = $product->product_minimum;
    $product_promo = $product->product_promo;
    $product_stock = $product->product_stock;
    $product_arrivaldate = $product->product_arrivaldate;
    $product_brandid = $product->product_brandid;
    $product_video = $product->product_video;
    $product_note = $product->product_note;
    $product_export = $product->product_export;
    $product_export1 = $product->product_export1;
    $product_fixedprice = $product->product_fixedprice;
    $product_toend = $product->product_toend;
    $product_dadd = $product->product_dadd;
    $product_dmod = $product->product_dmod;
    $product_price_1_eur = $product->product_price_1_eur;
    $product_price_1_huf = $product->product_price_1_huf;
    $product_price_1_ron = $product->product_price_1_ron;
    $product_price_retail_huf = $product->product_price_retail_huf;
    $product_price_retail_eur = $product->product_price_retail_eur;
    $product_price_retail_ron = $product->product_price_retail_ron;
    $product_pic1 = $product->product_pic1;
    $product_pic2 = $product->product_pic2;
    $product_pic3 = $product->product_pic3;
    $product_marks = $product->product_marks;
    $product_names = $product->product_names;
    $product_name = $product_names->product_name;
    $product_name_language = $product_name->product_name_language;
    $product_name_data = $product_name->product_name_data;
    $product_descriptions = $product->product_descriptions;
    $q = $conn->prepare($sql);
    $q->execute(array(
        ':product_id' => $product_id,
        ':product_code' => $product_code,
        ':product_ean' => $product_ean,
        ':product_active' => $product_active,
        ':product_pricecategory' => $product_pricecategory,
        ':product_maingroup' => $product_maingroup,
        ':product_subgroup' => $product_subgroup,
        ':product_minimum' => $product_minimum,
        ':product_promo' => $product_promo,
        ':product_stock' => $product_stock,
        ':product_arrivaldate' => $product_arrivaldate,
        ':product_brandid' => $product_brandid,
        ':product_video' => $product_video,
        ':product_note' => $product_note,
        ':product_export' => $product_export,
        ':product_export1' => $product_export1,
        ':product_fixedprice' => $product_fixedprice,
        ':product_toend' => $product_toend,
        ':product_dadd' => $product_dadd,
        ':product_dmod' => $product_dmod,
        ':product_price_1_eur' => $product_price_1_eur,
        ':product_price_1_huf' => $product_price_1_huf,
        ':product_price_1_ron' => $product_price_1_ron,
        ':product_price_retail_huf' => $product_price_retail_huf,
        ':product_price_retail_eur' => $product_price_retail_eur,
        ':product_price_retail_ron' => $product_price_retail_ron,
        ':product_pic1' => $product_pic1,
        ':product_pic2' => $product_pic2,
        ':product_pic3' => $product_pic3,
        ':product_marks' => $product_marks,
        ':product_name_language' => $product_name_language,
        ':product_name_data' => $product_name_data,
        ':product_descriptions' => $product_descriptions
    ));
}
foreach ($products->product as $prod1 => $productcode) {
    $product_id = $product->product_id;
    $product_code = $productcode->product_code;
    foreach ($productcode->product_properties as $prop => $property) {
        $product_property = $property->product_property;
        $q = $conn->prepare($sql_prop);
        $q->execute(array(
            ':product_id' => $product_id,
            ':product_code' => $product_code,
            ':product_property' => $product_property
        ));
    }
}