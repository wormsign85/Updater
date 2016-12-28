<?php

error_reporting(E_ALL);
set_time_limit(600);
require_once 'lib/init.php';

///////////////////////////////////////////////
// init
function get_orders_unas($client, $config, $config_db_wormsign) {
    try {

        $conn = new PDO($config_db_wormsign['connection'], $config_db_wormsign['username'], $config_db_wormsign['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
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
            //'Sku' => 2058527460,
            'DateStart' => "2001.02.04.",
            'DateEnd' => "2100.12.31",
            'ContentType' => 'full'
        );
        $response = $client->getProduct($auth, $params);
    } catch (SoapFault $error) {
        echo "<strong>getOrder Error:</strong><br /> ";
        echo "<pre>" . print_r($error, true) . "</pre>";
    }


        $products = new SimpleXMLElement($response);
    file_put_contents('products.xml', $products->asXML());
/*
    $xml = file_get_contents('products.xml');
    $products = new SimpleXMLElement($xml);
//    echo count($products->Product);
//    exit;

    $products = new SimpleXMLElement($response);

    $xml = file_put_contents($response, 'full_db.xml');
    $products = file_get_contents($xml);

    $sqlutf = "set names 'utf8'";
    $sth = $conn->prepare($sqlutf);
    $statement = $sth->execute();

    $sql = "REPLACE INTO product_urls SET categories=:categories,code=:code,product_url=:product_url,product_params=:product_params";

    $sql_params = "REPLACE INTO wormsignh_haffner.product_params SET szla_id=:szla_id,Id=:Id,Name=:Name,Type=:Type, Value=:Value";


    foreach ($products->Product as $sorszam => $product) {
        $sku = $product->Sku;
        $url = $product->Url;

        $categIds = array();
        foreach ($product->Categories->Category as $category) {
            $alt_cat_name1 = $category->Name;
            $alt_type = $category->Type;
            $categIds[] = $category->Id;
        }

        $categories = implode(',', $categIds);

        $paramIds = array();
        $paramValue = array();
        $paramType = array();
        $paramName = array();
        foreach ($product->Params->Param as $param) {
            $paramIds[] = $param->Id;
            $parType[] = $param->Type;
            $parName[] = $param->Name;
            $parValue[] = $param->Value;
        }

        $parIds = implode(',', $paramIds);
        $parValues = implode(',', $parValue);
        $parTypes = implode(',', $parType);
        $parNames = implode(',', $parName);

        $q = $conn->prepare($sql);
        $q->execute(array(
            ':categories' => $categories,
            ':code' => $sku,
            ':product_url' => $url,
            ':product_params' => $parIds
        ));

        $q = $conn->prepare($sql_params);
        $q->execute(array(
            ':szla_id' => $sku,
            ':Id' => $parIds,
            ':Name' => $parNames,
            ':Type' => $parTypes,
            ':Value' => $parValues
        ));
    }
    echo $products->asXML();*/
}

//require 'update_pro_sku.php';

get_orders_unas($client, $config['tokotveszek'], $config_db_wormsign);

