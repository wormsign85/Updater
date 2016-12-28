<?php

set_time_limit(6000);

require_once 'lib/init.php';
//require_once 'lib/actions.php';
require_once 'szetbont_marka_tipus.php';

function szetbont($s) {
    $tmp = preg_split('/[\s*]{2,}/', $s);

    $ret = array();
    foreach ($tmp as $t) {
        if (trim($t)) {
            $ret[] = $t;
        }
    }

    return $ret;
}

ata_mysql_query("set names 'utf8'", $connection);


$map = array();
$result = mysql_query('SELECT sku, compatible_devices FROM wormsignh_haffner.products');
while ($row = mysql_fetch_assoc($result)) {
    $termekek = szetbont($row['compatible_devices']);
    $altCat = array();
    foreach ($termekek as $markaestipus) {
        $markaestipusarray = szetbontMarkaEsTipus($markaestipus);
        if ($markaestipusarray) {
            $altCat[] = 'Márka' . '|' . $markaestipusarray['marka'] . '|' . $markaestipusarray['tipus'];
        }
    }

    $altCatString = implode('@', $altCat);

    $letrehoz = mysql_query('UPDATE wormsignh_haffner.products SET alt_cat="' . addslashes($altCatString) . '" WHERE sku="' . addslashes($row['sku']) . '"');
    if (!$letrehoz) {
        die('Could not connect: ' . mysql_error());
    }
}

echo 'Alternativ kategoriák legenerálva<br/>';
