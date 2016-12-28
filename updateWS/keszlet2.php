<?php

require_once 'lib/init.php';
set_time_limit(600);
//connect to the database 

require_once 'gt_keszlet.php';

$connect = mysql_connect("localhost", $config_db['username'], $config_db['password']);
mysql_select_db("wormsignh_wormsign_hu", $connect); //select the table


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://haffner24.hu/prod_short.php?id=522&pass=b9d3f1256139bb019736e0025d1dcd0d");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$csv = curl_exec($ch);
curl_close($ch);


ata_mysql_query("set names 'utf8'", $connection);

if ($csv) {

    //get the csv file 
    $csvLines = str_getcsv($csv, "\n"); //parse the rows 
    foreach ($csvLines as $i => $line) {
        if (0 == $i) {
            continue; // első sor a fejléc, átugorjuk
        }
        $row = str_getcsv($line, ";");
        echo 'Cikkszám: ' . $row[0] . ' | ' . $row[1] . ' db' . '<br/>';

        $result = ata_mysql_query("UPDATE tps_webshop SET keszlet_new='"
                . addslashes($row[1]) . "',keszletdate_new=current_timestamp WHERE cameron_sku='"
                . addslashes($row[0]) . "'
      ");

        echo 'haffner termékek frissítve tps_webshop wormsign.hu <br/>';

        $result = ata_mysql_query("UPDATE tps_webshop SET keszlet='"
                . addslashes($row[1]) . "',keszletdate=current_timestamp WHERE cameron_sku='"
                . addslashes($row[0]) . "'
      ");

        echo 'haffner termékek frissítve tps_webshop wormsign.hu <br/>';

        $result = ata_mysql_query("UPDATE wormsignh_haffner.products SET stock='"
                . addslashes($row[1]) . "' WHERE sku='" . addslashes($row[0]) . "'
      ");

        echo 'haffner termékek frissítve haffner db saját termékek tábla <br/>';
        
                $result = ata_mysql_query("UPDATE wormsignh_haffner.product_xref SET stock='"
                . addslashes($row[1]) . "' WHERE sku='" . addslashes($row[0]) . "'
      ");

        echo 'haffner termékek frissítve product_xref tábla <br/>';

        $result = ata_mysql_query("UPDATE wormsignh_update.stock SET haff_keszlet='"
                . addslashes($row[1]) . "' WHERE cameron_sku='" . addslashes($row[0]) . "'
      ");

        echo 'Stock frissítve készlet táblában <br/>';
    }
} else {
    echo 'Hiba: Nem sikerült adatot lekérni!';
}

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
    SET keszlet_new='4',
    keszlet='4'
    WHERE gigatel_stock='készleten' and gigatel_id!=''
       ");

echo 'keszlet feltöltes gigatel ami készletenvan az 4 db <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
    SET keszlet_new='0',
    keszlet='0'
    WHERE gigatel_stock NOT LIKE '%készleten%' and gigatel_id!=''
       ");

$result = ata_mysql_query("
UPDATE wormsignh_haffner.products SET export=1 WHERE stock!=0
  ");

$result = ata_mysql_query("
UPDATE wormsignh_haffner.products SET export=0 WHERE stock=0
  ");


$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
    SET export=1 WHERE keszlet_new!=0 and haffner_id!=''
       ");

echo 'export státusz = 1 <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
    SET export=0 WHERE keszlet_new=0 and haffner_id!=''
       ");


echo 'export státusz = 0 <br/>';

/*
  $result = mysql_query("UPDATE wormsignh_wormtest.tps_webshop SET keszlet_new='" . addslashes($row[1]) . "', keszletdate_new=current_timestamp WHERE cameron_sku='" . addslashes($row[0]) . "'
  ");
  if (!$result) {
  die('Invalid query: ' . mysql_error());
  }

  echo 'Wormtest frissítve tps webshopban, keszlet_new <br/>';

  $result = mysql_query("UPDATE wormsignh_wormtest.tps_webshop SET haff_keszlet='" . addslashes($row[1]) . "', haffner_update=current_timestamp WHERE cameron_sku='" . addslashes($row[0]) . "'
  ");
  if (!$result) {
  die('Invalid query: ' . mysql_error());
  }

  echo 'Wormtest frissítve haff_keszlet <br/>';
 */







require_once 'stock_upload_tokotveszek.php';
require_once 'stock_haffner_akkutkeresek.php';
