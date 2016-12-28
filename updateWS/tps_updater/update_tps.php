<?php

header('Content-Type: text/html; charset=utf-8');
require_once '../lib/init.php';
//connect to the database 
try {
    $conn = new PDO($config_db_wormsign['connection'], $config_db_wormsign['username'], $config_db_wormsign['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

//header('Content-Type: text/html; charset=utf-8');

ata_mysql_query("set names 'utf8'", $connection);

$kapac_db = 'wormsignh_wormsign_hu';


$csv = file_get_contents('tps_webshop.csv');

if ($csv) {

//get the csv file
    $csvLines = str_getcsv($csv, "\n"); //parse the rows
    foreach ($csvLines as $i => $line) {
        if (0 == $i) {
            continue; // első sor a fejléc, átugorjuk
        }
        $row = str_getcsv($line, ";
");
        echo '<b>Cikkszám:</b> ' . $row[0] . ' | ' . '</br><b>Kapacitas:</b>' . $row[1] . '</br>' . 
                'Feszültség:' . $row[2] . '</br>';

        $result = ata_mysql_query(" UPDATE wormsignh_wormsign_hu.tps_webshop SET"
                . " kapacitas = '" . addslashes($row[1]) . "'"
                . ", feszultseg = '" . addslashes($row[2]) . "'"
                . " WHERE id = '" . addslashes($row[0]) . "'
");
    }
} else {
    echo 'Hiba: Nem sikerült adatot lekérni!';
}