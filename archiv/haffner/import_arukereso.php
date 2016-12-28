<?php

set_time_limit(600);

require_once 'lib/init.php';

///////////////////////////////////////////////
// init


try {
    $conn = new PDO($config_db_akkutkeresek['connection'], $config_db_akkutkeresek['username'], $config_db_akkutkeresek['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$curl_user_pass = array(
    'url' => 'https://shop.unas.hu/admin_config_connect_compare.php?action=download&format=arukereso.hu',
    'user' => 'wormsign',
    'pass' => 'ixon14'
);

function get_stock_price_url($curl_user_pass) {
    // URL to login page
    $url = $curl_user_pass['url'];

    $ch = curl_init();

    $userpassinfo = array('login' => $curl_user_pass['user'], 'jelszo' => $curl_user_pass['pass']);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($userpassinfo));

    $xml = curl_exec($ch);

    return $xml;
}
$xml1= file_get_contents('arukereso_hu1.xml');

echo get_stock_price_url($curl_user_pass);

$xml = get_stock_price_url($curl_user_pass);

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

$products = new SimpleXMLElement($xml1);

//http://haffner24.hu/prod_short.php?id=522&pass=b9d3f1256139bb019736e0025d1dcd0d

print_r($xml);

$sqlutf = "set names 'utf8'";
$sth = $conn->prepare($sqlutf);
$statement = $sth->execute();

$sql = "REPLACE INTO product_urls SET code=:code,product_url=:product_url";



foreach ($products->product as $sorszam => $product) {
    $code = $product->code;
    $url = $product->product_url;
   

//    $cat_conv = array(">" => "|");
//    $cat_new = strtr($categories, $cat_conv);


    $q = $conn->prepare($sql);
    $q->execute(array(
        ':code' => $code,
        ':product_url' => $url
    ));
}


ata_mysql_query("set names 'utf8'", $connection);

//$result = ata_mysql_query("
//  UPDATE wormsignh_wormsign_hu.tps_webshop pp
//INNER JOIN wormsignh_wormsign_hu.product_urls w ON(pp.szla_id=w.code)
//SET pp.product_url=w.product_url  
//");


echo 'Új termékek átmásolva tps_webshopba' . '<br/>';