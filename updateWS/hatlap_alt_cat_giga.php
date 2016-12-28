<?php

set_time_limit(6000);

require_once 'lib/init.php';
//require_once 'lib/actions.php';
require_once 'lib/szetbont_marka_tipus.php';

function szetbont($s) {
    //$tmp = preg_split('/[\s*]{2,}/', $s);
    $tmp = explode(',', $s);

    $ret = array();
    foreach ($tmp as $t) {
        if (trim($t)) {
            $ret[] = $t;
        }
    }

    return $ret;
}

ata_mysql_query("set names 'utf8'", $connection);

function jonekunk($devices) {
    $mijo = array(
        'samsung galaxy s',
        'samsung galaxy j',
        'galaxy note',
        'galaxy tab',
        'Galaxy a',
        'galaxy ace',
        'apple',
        'ipad',
        'iphone'
    );

    foreach ($mijo as $string) {
        if (false !== mb_stripos($devices, $string)) {
            return true;
        }
    }

    return false;
}

$map = array();
$result = ata_mysql_query("SELECT * FROM wormsignh_haffner.gigatel WHERE new_main_category LIKE '%hátlap%'");

while ($row = mysql_fetch_assoc($result)) {
    $jonekunk = jonekunk($row['compatible_devices_new']);
    if (!$jonekunk) {
        ata_mysql_query('UPDATE wormsignh_haffner.gigatel SET alt_cat="" WHERE sku="' . addslashes($row['sku']) . '"');
        continue;
    }

    $termekek = szetbont($row['compatible_devices_new']);
    $altCat = array();
    foreach ($termekek as $markaestipus) {
        if (jonekunk($markaestipus)) {
            $markaestipusarray = szetbontMarkaEsTipus($markaestipus);
            if ($markaestipusarray) {
                $altCat[] = 'KERESO' . '|' . $markaestipusarray['marka'] . '|' . $markaestipusarray['tipus'] . '|' . 'Telefon Hátlap';
            }
        }
    }

    $altCatString = implode('@', $altCat);

    $letrehoz = ata_mysql_query('UPDATE wormsignh_haffner.gigatel SET alt_cat="' . addslashes($altCatString) . '" WHERE sku="' . addslashes($row['sku']) . '"');
}

echo 'Alternativ kategoriák legenerálva<br/>';




//$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
//  INNER JOIN wormsignh_haffner.products w ON (w.id = pp.haffner_id)
//  SET pp.alt_cat=w.alt_cat
//");
//
//echo 'ALkategoriák másolása élesbe<br>';
