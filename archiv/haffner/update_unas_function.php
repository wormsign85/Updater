<?php

//require_once 'lib/configlocal.php';
require_once 'lib/init.php';

function log_unas($filename, $message) {
    file_put_contents($filename, date('Y-m-d H:i:s') . ' ' . $message . "\n", FILE_APPEND);
}
/*
//Unas API kapcsolat
function get_unas_client() {
    $soap_server = "https://api.unas.eu/shop/?wsdl";
    ini_set("soap.wsdl_cache_enabled", "0");
///////////////////////////////////////////////
// auth
///////////////////////////////////////////////
// connect
    $client = new SoapClient($soap_server);
    return $client;
}
*/
/*
  function get_stock_price_url() {
  // URL to login page
  $url = "https://b2b.akku.hu/data/ar-keszlet.xml";

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  // curl_setopt($ch, CURLOPT_FILE, $out);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERPWD, "unas:g3r0n1m0");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  $xml = curl_exec($ch);
  return $xml;
  } */

function pdo_connection($config_ws) {
    try {
        $pdo_conn = new PDO($config_ws['connection'], $config_ws['username'], $config_ws['password']);
        $pdo_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    return $pdo_conn;
}

$pdo_conn = pdo_connection($config_ws);

//$get_xml = get_stock_price_url();

$client = get_unas_client();

///////////////////////////////////////////////
// init
function set_price_unas($client, $pdo_conn) {

    //$logfilename = $systemConfig['log_dir'] . '/' . $webshopName . '_' . $soapConfig['ShopId'];

    $sql1 = "set names 'utf8'";
    $sth = $pdo_conn->prepare($sql1);
    $statement = $sth->execute();

    $sql_update = "SELECT id, cameron_sku, megnevezes, active from tps_webshop WHERE export=1 AND updated_at>'" . date('Y-m-d H:i:s', time() - 1 * 24 * 3600) . "'";
    $sth = $pdo_conn->prepare($sql_update);
    $statement = $sth->execute();
    $products = $sth->fetchAll();

    header('content-type: text/xml');

//$xml = file_get_contents('stocks_kapac.xml');

    $header = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Products/>');

    foreach ($products as $i => $row) {
            $product = $header->AddChild('Product');
            $noerrorst = $product->AddChild('StopOnError', 'no');
            $sku = $product->AddChild('Sku', $row['id']);
            $name = $product->AddChild('Name', $row['megnevezes']);
            $statuses = $product->AddChild('Statuses');
            $status = $statuses->AddChild('Status');
            $type = $status->AddChild('Type', 'base');
            $value = $status->AddChild('Value', $row['active']);
    }
    echo $header->asXML();

    try {
        $auth = array(
            'Username' => 'akkumulator',
            'PasswordCrypt' => '93c7e876a2b0be57c1d96247831a2b13',
            'ShopId' => '7171',
            'AuthCode' => '2b361baa70'
        );

        // Tesztadatok cseréje
        // file_put_contents ('unas_order.xml', $response);

        $response = $client->setProductXML($auth, $header->asXML());
    } catch (SoapFault $error) {
        log_unas($logfilename, 'SoapError: ' . $error->getMessage());
        echo "<strong>SetProductXML Error:</strong><br /> ";
        echo "<pre>" . print_r($error, true) . "</pre>";
    }
}

    set_price_unas($client, $pdo_conn);
