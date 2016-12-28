<?php

require_once '../lib/init.php';
set_time_limit(600);
//connect to the database 
try {
    $conn = new PDO($config_db_wormsign['connection'], $config_db_wormsign['username'], $config_db_wormsign['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
//
//$connect = mysql_connect("localhost", $config_db['username'], $config_db['password']);
//mysql_select_db("wormsignh_kapacitas", $conn); //select the table
//header('Content-Type: text/html; charset=utf-8');


$curl_user_pass = array(
    'url' => 'http://datafeed.batterysearch.hu/csv_export.php?feedid=wormsign',
    'user_pass' => 'kapacitas:octopus'
);

// URL to login page
//$url = $curl_user_pass['url'];
//
//$ch = curl_init();
//
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//// curl_setopt($ch, CURLOPT_FILE, $out);
//curl_setopt($ch, CURLOPT_HEADER, 0);
//curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_USERPWD, $curl_user_pass['user_pass']);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//curl_exec($ch);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://datafeed.batterysearch.hu/csv_export.php?feedid=wormsign");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$csv = curl_exec($ch);
curl_close($ch);

//$csv = file_get_contents('export-wormsign.csv');

ata_mysql_query("set names 'utf8'", $connection);


$sku_conv = array("mm" => "", "AKKU.HU|" => "", "ELEM.HU|" => "", "Standard akkuk, töltők|" => "Akkuk és Töltők|"
    , "Világítástechnika|Panasonic Világítástechnika|LED Égők" => "Világítástechnika|LED Égők"
    , "Márka|Skross" => "SKROSS Átalakítók", "Ipari akkumulátorok" => "MOBIL_Ipari Akku Cellák",
    "Ipari lítium elemek" => "MOBIL_Líthium Elemek", "Üzletbe, munkához|Szerszámgép Akkuk" => "MOBIL_Utángyártott Szerszámgép akkuk");

if ($csv) {

//get the csv file
    $csvLines = str_getcsv($csv, "\n"); //parse the rows
    foreach ($csvLines as $i => $line) {
        if (0 == $i) {
            continue; // első sor a fejléc, átugorjuk
        }
        $row = str_getcsv($line, ";");
        echo '<b>Cikkszám:</b> ' . $row[0] . ' | ' . '</br><b>Név:</b>' . $row[1] . '</br><b>Ár:</b>' . $row[3] . 'Ft <br/></br>';



        $result = ata_mysql_query("INSERT IGNORE INTO wormsignh_wormsign_hu.tps_webshop"
                . "(cameron_sku, megnevezes, ar, keszlet,kapacitas,kapac_category,image_url,"
                . "leiras_hosszu,marka,feszultseg,osszetetel)"
                . " SELECT '" . addslashes($row[0]) . "'"
                . " ,'" . addslashes($row[1]) . "'"
                . ", '" . addslashes($row[3]) . "'"
                . ", '" . addslashes($row[4]) . "'"
                . ", '" . addslashes($row[24]) . "'"
                . ", '" . strtr($row[10], $sku_conv) . "'"
                . ", '" . addslashes($row[9]) . "'"
                . ", '" . addslashes($row[6]) . "'"
                . ", '" . addslashes($row[14]) . "'"
                . ", '" . addslashes($row[16]) . "'"
                . ", '" . addslashes($row[62]) . "'

");
    }
} else {
    echo 'Hiba: Nem sikerült adatot lekérni!';
}
echo '<br/><br/>Kapacitás termékeinek importálása <br/><br/>';


if ($csv) {

//get the csv file
    $csvLines = str_getcsv($csv, "\n"); //parse the rows
    foreach ($csvLines as $i => $line) {
        if (0 == $i) {
            continue; // első sor a fejléc, átugorjuk
        }
        $row = str_getcsv($line, ";");

//        $result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop SET keszlet_new = '"
//                . addslashes($row[4]) . "', keszletdate_new = current_timestamp WHERE cameron_sku = '"
//                . addslashes($row[0]) . "'
//");
//
//        $result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop SET keszlet = '"
//                . addslashes($row[4]) . "', keszletdate = current_timestamp WHERE cameron_sku = '"
//                . addslashes($row[0]) . "'
//");
//        $result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop SET ar = '"
//                . addslashes($row[3]) . "', keszletdate = current_timestamp WHERE cameron_sku = '"
//                . addslashes($row[0]) . "'
//");

        $result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop "
                . "SET marka = '" . addslashes($row[14]) . "'"
                . ", kapacitas = '" . addslashes($row[24]) . "'"
                . ", feszultseg = '" . addslashes($row[16]) . "'"
                . ", image_url = '" . addslashes($row[9]) . "'"
                . ", kapac_category = '" . strtr($row[10], $sku_conv) . "'"
                . ", ak_category = '" . strtr($row[10], $sku_conv) . "'"
                . ", osszetetel = '" . addslashes($row[60]) . "'"
                . ", meret = '" . addslashes($row[15]) . "'"
                . ", magassag = '" . addslashes(strtr($row[17], $sku_conv)) . "'"
                . ", szelesseg = '" . addslashes(strtr($row[44], $sku_conv)) . "'"
                . ", melyseg = '" . addslashes(strtr($row[45], $sku_conv)) . "'"
                . ", atmero = '" . addslashes(strtr($row[18], $sku_conv)) . "'"
                . "WHERE cameron_sku = '" . addslashes($row[0]) . "'
");
        echo '<b>Cikkszám:</b> ' . $row[0] . ' | ' . '</br><b>Kategória:</b>' . $row[10] . ' <br/></br>';
    }
} else {
    echo 'Hiba: Nem sikerült adatot lekérni!';
}
echo '<br/><br/>Kapacitás készlet és ár importálása <br/><br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET active=1
  WHERE kapac_category!=''
  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET active=IF(keszletdate_new<'" . date('Y-m-d H:i:s', time() - 1 * 24 * 3600) . "','0','1')
  WHERE kapac_category!=''
  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET leiras_hosszu='<p>Vállaljuk nagy mennyiségű ólom akkumulátorok szállítását, telepítését is.</p>
<p>&nbsp; </p>
<p>Amennyiben gyári adatlapra, specifikációkra van szüksége kérem, jelezze ezt nekünk a cikkszám megjelölésével.</p>
<p>Ha műszaki kérdése merülne fel, készséggel állunk rendelkezésére! </p>'
  WHERE kapac_category LIKE '%ólom%'
  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET vasarolhato_ha_nincs_raktaron='1'
  WHERE megnevezes LIKE '%felújítás%' OR megnevezes LIKE '%pakk%'
  ");


//require_once '../stock_upload_akkucentral.php';
