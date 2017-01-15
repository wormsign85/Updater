<?php

set_time_limit(6000);

require_once 'lib/init.php';
//require_once 'actions.php';


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

$map = array();
$result = mysql_query('SELECT sku, compatible_devices FROM wormsignh_haffner.products');
while ($row = mysql_fetch_assoc($result)) {
    $termekek = szetbont($row['compatible_devices']);
    foreach ($termekek as $termek) {
        $letrehoz = mysql_query('REPLACE INTO wormsignh_haffner.xref SET sku="' . addslashes($row['sku']) . '", xref="' . addslashes($termek) . '"');
        if (!$letrehoz) {
            die('Could not connect: ' . mysql_error());
        }
    }
}
//
//$result = ata_mysql_query('SELECT sku FROM wormsignh_haffner.products WHERE stock>2');
//while ($row = mysql_fetch_assoc($result)) {
//    $result1 = ata_mysql_query('SELECT szla_id,sku,xref FROM wormsignh_haffner.haffner_xref WHERE sku="' . $row['sku'] . '" AND stock>2');
//    $newParts = array();
//    while ($row1 = mysql_fetch_assoc($result1)) {
//        if (!empty($row1['szla_id'])) {
//            $newParts[] = $row1['szla_id'];
//        }
//    }
//    shuffle($newParts);
//    array_splice($newParts, 5);
//    $kiegeszitok = implode('|', $newParts);
//    ata_mysql_query('UPDATE wormsignh_haffner.products SET kiegeszitok="' . addslashes($kiegeszitok) . '" WHERE sku="' . $row['sku'] . '"');
//}
//echo 'Kiegészítők legenerálva<br/>';