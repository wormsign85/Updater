<?php

set_time_limit(600);
//connect to the database 

$connect = mysql_connect("localhost", "wormsignh_worm", "IxOn1985");
mysql_select_db("wormsignh_haffner", $connect); //select the table


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://haffner24.hu/prod_short.php?id=522&pass=b9d3f1256139bb019736e0025d1dcd0d");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$csv = curl_exec($ch);
curl_close($ch);

if ($csv) {

    //get the csv file 
    $csvLines = str_getcsv($csv, "\n"); //parse the rows 
    foreach ($csvLines as $i => $line) {
        if (0 == $i) {
            continue; // első sor a fejléc, átugorjuk
        }
        $row = str_getcsv($line, ";");
        echo 'Cikkszám: '. $row[0] . ' | ' . $row[1] . ' db' . '<br/>';



        $result = mysql_query("UPDATE sajat_termekek SET stock='" . addslashes($row[1]) . "' WHERE sku='" . addslashes($row[0]) . "'
      ");
        if (!$result) {
            die('Invalid query: ' . mysql_error());
        }

        echo 'haffner termékek frissítve <br/>';

        $result = mysql_query("UPDATE wormsignh_update.stock SET haff_keszlet='" . addslashes($row[1]) . "' WHERE cameron_sku='" . addslashes($row[0]) . "'
      ");
        if (!$result) {
            die('Invalid query: ' . mysql_error());
        }

        echo 'Stock frissítve <br/>';
        
        
        $result = mysql_query("UPDATE wormsignh_wormtest.tps_webshop SET keszlet_new='" . addslashes($row[1]) . "', keszletdate_new=current_timestamp WHERE cameron_sku='" . addslashes($row[0]) . "'
       ");
        if (!$result) {
            die('Invalid query: ' . mysql_error());
        }

        echo 'Wormtest frissítve <br/>';

        $result = mysql_query("UPDATE wormsignh_wormtest.tps_webshop SET haff_keszlet='" . addslashes($row[1]) . "', haffner_update=current_timestamp WHERE cameron_sku='" . addslashes($row[0]) . "'
       ");
        if (!$result) {
            die('Invalid query: ' . mysql_error());
        }

        echo 'Wormtest frissítve <br/>';
        
        
    }
} else {
    echo 'Hiba: Nem sikerült adatot lekérni!';
}
