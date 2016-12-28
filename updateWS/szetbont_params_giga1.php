<?php

set_time_limit(6000);

require_once 'lib/init.php';
//require_once 'lib/actions.php';
require_once 'lib/szetbont_nev_params_giga1.php';

ata_mysql_query("set names 'utf8'", $connection);


$map = array();
$result = mysql_query("SELECT sku, compatible_devices FROM wormsignh_haffner.gigatel");
while ($row = mysql_fetch_assoc($result)) {
    $oszlopok = szetbontTulajdonsagok($row['compatible_devices']);
    if ($oszlopok) {
        $sqlOszlopok = array();
        foreach ($oszlopok as $oszlop => $ertek) {
            $sqlOszlopok[] = $oszlop . '=' . '"' . addslashes($ertek) . '"';
        }

        $setek = implode(',', $sqlOszlopok);
        $letrehoz = mysql_query('UPDATE wormsignh_haffner.gigatel SET ' . $setek . ' WHERE sku="' . addslashes($row['sku']) . '"');
        if (!$letrehoz) {
            die('Could not connect: ' . mysql_error());
        }
    }
}
echo 'Paraméterek kiírva gt_tokshop db';
//
//$result = ata_mysql_query("update wormsignh_haffner.gt_tokshop
//set full_main_category=CONCAT(category, '|', subcategory, '|', cat_brand)
//  ");

