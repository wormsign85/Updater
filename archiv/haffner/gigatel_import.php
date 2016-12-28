<?php

require_once 'lib/init.php';
set_time_limit(600);
//connect to the database 

//$connect = mysql_connect("localhost", $config_db_gigatel['username'], $config_db_gigatel['password']);
//mysql_select_db($config_db_gigatel['select_db'], $connect); //select the table

//ata_mysql_query("set names 'utf8'", $connect);



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.gigatel.hu/pic/94C1ECDC_ws.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$csv = curl_exec($ch);
curl_close($ch);

//$csv = file_get_contents('gigatel_cikklist.csv');

if ($csv) {

    //get the csv file 
    $csvLines = str_getcsv($csv, "\n"); //parse the rows 

    foreach ($csvLines as $i => $line) {
        if (0 == $i) {
            continue; // első sor a fejléc, átugorjuk
        }
        $row = str_getcsv($line, ';', '"');
        // echo 'Cikkszám: ' . $row[0] . ' | ' . $row[1] . ' db' . '<br/>';

        $result = ata_mysql_query("INSERT IGNORE INTO wormsignh_haffner.gigatel SET cikkszam='" . addslashes($row[0]) . "',
                                                      cikknev='" . addslashes($row[1]) . "',
                                                      sku='" . addslashes($row[2]) . "',
                                                      cikkfajta='" . addslashes($row[3]) . "',
                                                      gyarto='" . addslashes($row[4]) . "',
                                                      allapot='" . addslashes($row[5]) . "',
                                                      cikkcsoport='" . addslashes($row[6]) . "',
                                                      keszletinfo='" . addslashes($row[7]) . "',
                                                      dev_netto='" . addslashes($row[8]) . "',
                                                      netto_kisker_ar='" . addslashes($row[9]) . "',
                                                      termekkep='" . addslashes($row[10]) . "',
                                                      termekkep2='" . addslashes($row[11]) . "',
                                                      kesobb_megjeleno_termek='" . addslashes($row[12]) . "',
                                                      compatible_devices='" . addslashes($row[13]) . "',
                                                      termekinfo='" . addslashes($row[14]) . "',
                                                      updated_at=NOW(),
                                                      letrehozva=NOW()
      ");
    }
} else {
    echo 'Hiba: Nem sikerült adatot lekérni!';
}