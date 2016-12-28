<?php

require_once 'lib/init.php';
set_time_limit(600);
//connect to the database 

$connect = mysql_connect("localhost", $config_db['username'], $config_db['password']);
mysql_select_db("wormsignh_wormsign_hu", $connect); //select the table


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.gigatel.hu/pic/94C1ECDC_ws.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$csv = curl_exec($ch);
curl_close($ch);

//mb_convert_encoding($csv, 'UTF-8');
//ata_mysql_query("set names 'utf8'", $connection);

if ($csv) {

    //get the csv file 
    $csvLines = str_getcsv($csv, "\n"); //parse the rows 
    foreach ($csvLines as $i => $line) {
        if (0 == $i) {
            continue; // első sor a fejléc, átugorjuk
        }
        $row = str_getcsv($line, ";");
        echo 'Cikkszám: ' . $row[0] . ' | ' . $row[7] . ' ' . '<br/>';

        $result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop SET gigatel_stock='" . addslashes($row[7]) . "',"
                . "keszletdate_new=current_timestamp WHERE gigatel_id='" . addslashes($row[0]) . "'
      ");

        $result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel SET "
                . "keszletinfo='" . addslashes($row[7]) . "',"
                . "keszletdate_new=NOW(),"
                . "dev_netto='" . addslashes($row[8]) . "',"
                . "netto_kisker_ar='" . addslashes($row[9]) . "'"
                . "WHERE cikkszam='" . addslashes($row[0]) . "'
      ");

//        $result = ata_mysql_query("UPDATE wormsignh_haffner.product_xref SET gt_stock='" . addslashes($row[7]) . "'"
//                . " WHERE sku='" . addslashes($row[2]) . "'
//      ");

        echo 'gigatel termékek frissítve tps_webshop wormsign.hu <br/>';
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

//
//$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
//    SET keszlet_new='4',
//    keszlet='4'
//    WHERE gigatel_stock='készleten' and gigatel_id!=''
//       ");
//
//echo 'keszlet feltöltes gigatel ami készletenvan az 4 db <br/>';
//
//$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
//    SET keszlet_new='0',
//    keszlet='0'
//    WHERE gigatel_stock NOT LIKE '%készleten%' and gigatel_id!=''
//       ");
//
//$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop SET export=1 WHERE keszlet>0 
//       ");
//
//
//echo 'export státusz = 1 <br/>';
////
//$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop SET export=0 WHERE keszlet=0
//       ");
//
//
//echo 'export státusz = 0 <br/>';

//require_once 'stock_upload_tokotveszek.php';
//require_once 'stock_haffner_akkutkeresek.php';
