<?php

set_time_limit(6000);

require_once 'lib/init.php';
//require_once 'lib/actions.php';
require_once 'lib/szetbont_marka_tipus.php';

try {
    $conn = new PDO($config_db['connection'], $config_db['username'], $config_db['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

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

$delete_alt_cats = "UPDATE wormsignh_haffner.products SET alt_cat=''";
$q = $conn->prepare($delete_alt_cats);
$q->execute();

echo 'Töröltük az alt_cat mezőt, hogy a frissek kerülhessenek bele.</br>';

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
        'iphone',
        'huawei'
    );

    foreach ($mijo as $string) {
        if (false !== mb_stripos($devices, $string)) {
            return true;
        }
    }

    return false;
}

$map = array();
$result = ata_mysql_query("SELECT * FROM wormsignh_haffner.products WHERE categories LIKE '%tokok%' AND categories NOT LIKE '%töltők%'");

while ($row = mysql_fetch_assoc($result)) {
    $jonekunk = jonekunk($row['compatible_devices_new']);
    if (!$jonekunk) {
        ata_mysql_query('UPDATE wormsignh_haffner.products SET alt_cat="" WHERE sku="' . addslashes($row['sku']) . '"');
        continue;
    }

    $termekek = szetbont($row['compatible_devices_new']);
    $altCat = array();
    foreach ($termekek as $markaestipus) {
        if (jonekunk($markaestipus)) {
            $markaestipusarray = szetbontMarkaEsTipus($markaestipus);
            if ($markaestipusarray) {
                $altCat[] = 'KERESO' . '|' . $markaestipusarray['marka'] . '|' . $markaestipusarray['tipus'] . '|' . 'Tok és táska';
            }
        }
    }

    $altCatString = implode('@', $altCat);

    $letrehoz = ata_mysql_query('UPDATE wormsignh_haffner.products SET alt_cat="' . addslashes($altCatString) . '" WHERE sku="' . addslashes($row['sku']) . '" AND alt_cat=""');
}

echo 'Alternativ kategoriák legenerálva (Tokok)<br/>';






//$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
//  INNER JOIN wormsignh_haffner.products w ON (w.id = pp.haffner_id)
//  SET pp.alt_cat=w.alt_cat
//");
//
//echo 'ALkategoriák másolása élesbe<br>';
