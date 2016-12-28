<?php

error_reporting(E_ALL);
set_time_limit(6000);
require_once 'lib/init.php';

///////////////////////////////////////////////
// init
function get_orders_unas($client, $config, $config_db_akkutkeresek) {
    try {

        $conn = new PDO($config_db_akkutkeresek['connection'], $config_db_akkutkeresek['username'], $config_db_akkutkeresek['password']);
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
            //'Sku' => 6320,
            'DateStart' => "2001.02.04.",
            'DateEnd' => "2100.12.31",
            'ContentType' => 'full'
        );
        $response = $client->getProduct($auth, $params);
    } catch (SoapFault $error) {
        echo "<strong>getProduct Error:</strong><br /> ";
        echo "<pre>" . print_r($error, true) . "</pre>";
    }

    $products = new SimpleXMLElement($response);
    file_put_contents('products_ak.xml', $products->asXML());

//    $xml = file_get_contents('products_ak.xml');
//    $products = new SimpleXMLElement($xml);
    /*
      $sqlutf = "set names 'utf8'";
      $sth = $conn->prepare($sqlutf);
      $statement = $sth->execute();

      $sql = "REPLACE INTO product_urls SET cat_id=:cat_id,code=:code,product_url=:product_url,catNames=:catNames,catTypes=:catTypes";

      $sql_params = "REPLACE INTO wormsignh_wormtest.product_params SET szla_id=:szla_id,Id=:Id,Name=:Name,Type=:Type, Value=:Value";


      foreach ($products->Product as $sorszam => $product) {
      $sku = $product->Sku;
      $url = $product->Url;

      $categIds = array();
      $categNames = array();
      $categTypes = array();

      foreach ($product->Categories->Category as $category) {
      $categNames[] = $category->Name;
      $categTypes[] = $category->Type;
      $categIds[] = $category->Id;
      }

      $catId = implode(',', $categIds);
      $catNames = implode(',', $categNames);
      $catType = implode(',', $categTypes);

      $q = $conn->prepare($sql);
      $q->execute(array(
      ':code' => $sku,
      ':cat_id' => $catId,
      ':product_url' => $url,
      ':catNames' => $catNames,
      ':catTypes' => $catType,
      ));

      $parId = array();
      $parType = array();
      $parName = array();
      $parValue = array();

      foreach ($product->Params->Param as $param) {
      $parId[] = $param->Id;
      $parType[] = $param->Type;
      $parName[] = $param->Name;
      $parValue[] = $param->Value;
      }
      $parIds = implode(',', $parId);
      $parTypes = implode(',', $parType);
      $parNames = implode(',', $parName);
      $parValues = implode(',', $parValue);

      $q = $conn->prepare($sql_params);
      $q->execute(array(
      ':szla_id' => $sku,
      ':Id' => $parIds,
      ':Type' => $parTypes,
      ':Name' => $parNames,
      ':Value' => $parValues,
      ));

      }
      echo $products->asXML(); */
}

//require 'update_pro_sku.php';

get_orders_unas($client, $config['akkutkeresek'], $config_db_akkutkeresek);

