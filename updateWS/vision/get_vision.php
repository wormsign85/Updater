<?php

//require_once '../lib/init.php';
//require_once '../lib/getcsv.php';

require_once 'init.php';
require_once 'getcsv.php';
set_time_limit(6000);
//connect to the database 
try {
    $conn = new PDO($config_kapac['connection'], $config_kapac['username'], $config_kapac['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

header('Content-Type: text/html; charset=utf-8');


//ata_mysql_query("set names 'utf8'", $connection);

$kapac_db = 'wormsignh_kapacitas';

//ata_mysql_query("set names 'utf8'", $connection);

$conv = array("-X" => "X", "12V" => "12", "-AM" => "", "-AT" => "");

$mertekConv = array("V" => "", " V" => "");

//itt behúzzuk az AX cikkszámokat
foreach (glob("vision*.csv") as $filename) {
//$filename = 'vision.csv';
    $csvLines = csv_to_array_assoc($filename);

//    $conv = array("-X" => "X");

    if ($csvLines) {
        echo ' ÁrLista: ' . $filename . '<br/>';

        foreach ($csvLines as $i => $row) {
            if (!$row['sku'])
                continue;

            echo 'Cikkszám: ' . $row['sku'] . ' <br/>';

            if (isset($row['Terminal']) && trim($row['Terminal']) != '') {
                $connector_parts = explode('/', $row['Terminal']);
            } else {
                $connector_parts = array('');
            }
            foreach ($connector_parts as $part) {
                $connector = trim($part);
                $data = array();
                if (isset($row['sku'])) {
                    $data['sku'] = strtr($row['sku'], $conv) . ($connector ? '-' . $connector : '');
                }
                if (isset($row['sku'])) {
                    $data['original_sku'] = $row['sku'];
                }
                if (isset($row['voltage'])) {
                    $data['voltage'] = strtr($row['voltage'], $mertekConv);
                }
                if (isset($row['10hr'])) {
                    $data['cap_10hr'] = strtr($row['10hr'], $mertekConv);
                }
                if (isset($row['20hr'])) {
                    $data['cap_20hr'] = strtr($row['20hr'], $mertekConv);
                }
                if (isset($row['10min'])) {
                    $data['10min'] = strtr($row['10min'], $mertekConv);
                }
                if (isset($row['15min'])) {
                    $data['15min'] = strtr($row['15min'], $mertekConv);
                }
                if (isset($row['25A_min'])) {
                    $data['25A_min'] = strtr($row['25A_min'], $mertekConv);
                }
                if (isset($row['75A_min'])) {
                    $data['75A_min'] = strtr($row['75A_min'], $mertekConv);
                }

                //innen jönnek a méretek
                if (isset($row['length_mm'])) {
                    $data['length_mm'] = $row['length_mm'];
                }
                if (isset($row['length_in'])) {
                    $data['length_in'] = strtr($row['length_in'], $conv);
                }
                if (isset($row['width_mm'])) {
                    $data['width_mm'] = $row['width_mm'];
                }
                if (isset($row['width_in'])) {
                    $data['width_in'] = strtr($row['width_in'], $conv);
                }
                if (isset($row['height_mm'])) {
                    $data['height_mm'] = $row['height_mm'];
                }
                if (isset($row['height_in'])) {
                    $data['height_in'] = strtr($row['height_in'], $conv);
                }
                if (isset($row['theight_mm'])) {
                    $data['theight_mm'] = $row['theight_mm'];
                }
                if (isset($row['weight_kg'])) {
                    $data['weight_kg'] = $row['weight_kg'];
                }
                if (isset($row['weight_pund'])) {
                    $data['weight_pound'] = strtr($row['weight_pund'], $conv);
                }
                if (isset($row['Terminal'])) {
                    $data['terminal'] = $connector;
                }
//                if (isset($row['sku'])) {
//                    $data['datasheet'] = 'http://www.vision-batt.com/site/product_files/' . $row['sku'] . '.pdf';
//                }

                if (isset($row['sku'])) {
                    $data['datasheet'] = 'http://www.vision-batt.eu/sites/default/files/public/docs/products/manuals/' . $row['sku'] . '.pdf';
                }


                $result = ata_mysql_query(ata_get_insert('wormsignh_kapacitas.vision', $data, true));
                /*
                  // Ide menti, í végére / -t írj!
                  // pl.: a get_sunny.php könyvtárában levő 'kepek' könyvtárba:
                  // $imgPath = dirname(__FILE__) . '/kepek/';
                  $imgPath = dirname(__FILE__) . '/pdf/';
                  // Kép letöltése és mentése, ha meg van adva image és ax_sku
                  if (!empty($data['datasheet']) && !empty(trim($data['sku']))) {
                  $imgData = file_get_contents($data['datasheet']);
                  if (false !== $imgData) {
                  file_put_contents($imgPath . trim(strtr($row['sku'], $conv) . ($connector ? '-' . $connector : '')) . '.pdf', $imgData);
                  } else {
                  echo 'Hiba: sikertelen letöltés: ' . $data['pdf'] . '<br/>' . "\n";
                  }
                  } */
            }
        }
    } else {
        echo 'Hiba: Nem sikerült adatot lekérni!';
    }
}

//require_once 'letoltes.php';
