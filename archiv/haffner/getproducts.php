<?php

set_time_limit(600);

require_once 'init.php';

///////////////////////////////////////////////
// init


try {
    $conn = new PDO($config_db['connection'], $config_db['username'], $config_db['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "http://haffner24.hu/prod_feed_xml.php?id=522&pass=b9d3f1256139bb019736e0025d1dcd0d");
  //curl_setopt($ch, CURLOPT_URL, "www.debranet.com/xml/xml.php?from=0&to=5000&onstock=1");;
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // Videos are needed to transfered in binary
  $xml = curl_exec($ch); // $result will have your video.
  curl_close($ch);
 
/*
  UPDATE wormsignh_haffner.sajat_termekek p
  INNER JOIN wormsignh_wormtest.tps_webshop_feltolt w ON(p.sku=w.cameron_sku)
  SET p.ws_kisker=w.ar/1.27
 * 
 * UPDATE wormsignh_haffner.sajat_termekek p
  INNER JOIN wormsignh_wormtest.tps_webshop_feltolt w ON(p.sku=w.cameron_sku)
  SET p.ws_price_besz=w.beszerar
 */
//$xml = file_get_contents('products.xml');

$products = new SimpleXMLElement($xml);

//http://haffner24.hu/prod_short.php?id=522&pass=b9d3f1256139bb019736e0025d1dcd0d

print_r($xml);


$sqlutf = "set names 'utf8'";
$sth = $conn->prepare($sqlutf);
$statement = $sth->execute();

$sql = "REPLACE INTO products SET sku=:sku,name=:name,id=:id,"
        . " categories=:categories,description=:description,manufacturer=:manufacturer,basic_price=:basic_price,price=:price,multiplier_price=:multiplier_price,"
        . " tax_rate=:tax_rate,short_description=:short_description,image_url=:image_url,compatible_devices=:compatible_devices,related_products=:related_products,all_category=:all_category,publish=:publish,youtube=:youtube";



foreach ($products->product as $sorszam => $product) {
    $sku = $product->sku;
    $name = $product->name;
    $id = $product->id;
    $categories = $product->categories->cat;
    $description = $product->description;
    $manufacturer = $product->manufacturer;
    $basic_price = $product->basic_price;
    $price = $product->price;
    $multiplier_price = $product->multiplier_price;
    $tax_rate = $product->tax_rate;
    $short_description = $product->short_description;
    $image_url = $product->image_url;
    $compatible_devices = $product->compatible_devices;
    $related_products = $product->related_products;
    $all_category = $product->all_category;
    $publish = $product->publish;
    $youtube = $product->youtube_link;

    $cat_conv = array(">" => "|");
    $cat_new = strtr($categories, $cat_conv);


    $q = $conn->prepare($sql);
    $q->execute(array(
        ':sku' => $sku,
        ':name' => $name,
        ':id' => $id,
        ':categories' => $cat_new,
        ':description' => html_entity_decode(strtr($description, array('&lt,' => '<', '&gt,' => '>'))),
        ':manufacturer' => $manufacturer,
        ':basic_price' => $basic_price,
        ':price' => $price,
        ':multiplier_price' => $multiplier_price,
        ':tax_rate' => $tax_rate,
        ':short_description' => $short_description,
        ':image_url' => $image_url,
        ':compatible_devices' => $compatible_devices,
        ':related_products' => $related_products,
        ':all_category' => $all_category,
        ':publish' => $publish,
        ':youtube' => $youtube
    ));
}
