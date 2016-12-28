<?php

set_time_limit(600);
require_once 'init.php';

///////////////////////////////////////////////
// init
function get_orders_unas($client, $config, $config_db_wormsign) {
    try {
        $auth = array(
            'Username' => $config['Username'],
            'PasswordCrypt' => $config['PasswordCrypt'],
            'ShopId' => $config['ShopId'],
            'AuthCode' => $config['AuthCode']
        );
//getOrder
        $params = array(
            'StatusBase' => 1,
//            'Sku' => 2058531049,
            'DateStart' => "2001.02.04.",
            'DateEnd' => "2100.12.31",
            'ContentType' => 'short'
        );
        $response = $client->getProduct($auth, $params);
    } catch (SoapFault $error) {
        echo "<strong>getOrder Error:</strong><br /> ";
        echo "<pre>" . print_r($error, true) . "</pre>";
    }

    try {

        $conn = new PDO($config_db_wormsign['connection'], $config_db_wormsign['username'], $config_db_wormsign['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    $products = new SimpleXMLElement($response);
    $sqlutf = "set names 'utf8'";
    $sth = $conn->prepare($sqlutf);
    $statement = $sth->execute();

    $sql = "REPLACE INTO product_urls SET categories=:categories,code=:code,product_url=:product_url, alt_cat_name=:alt_cat_name";

    foreach ($products->Product as $sorszam => $product) {
        $sku = $product->Sku;
        $url = $product->Url;

        $categIds = array();
        foreach ($product->Category as $category) {
            $alt_cat_name1 = $category->Name;
            $alt_type = $category->Type; 
            $categIds[] = $category->Id;
        }
        
        $categories = implode(',', $categIds);
        
        $q = $conn->prepare($sql);
        $q->execute(array(
            'categories' => $categories,
            ':code' => $sku,
            ':product_url' => $url,
        ));

    }
    echo $products->asXML();
}

//require 'update_pro_sku.php';

get_orders_unas($client, $config['akkucentral'], $config_db_wormsign);

