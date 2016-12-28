<?php

set_time_limit(6000);

require_once 'lib/init.php';
//require_once 'lib/actions.php';
require_once 'lib/szetbont_marka_tipus.php';
//require_once 'repair_compatibles.php';

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
$select_product = mysql_query("SELECT cikkszam FROM wormsignh_haffner.gigatel");



while ($row = mysql_fetch_assoc($select_product)) {

    $select_xref = mysql_query("SELECT xref_new FROM wormsignh_haffner.gigatel_xref WHERE cikkszam='"
            . addslashes($row['cikkszam']) . "' AND xref_new!=''");
    $xref_list = array();

    while ($row_xref = mysql_fetch_array($select_xref)) {
        $xref_list[] = $row_xref['xref_new'];
    }
    $xref_list = array_unique($xref_list);
    $osszefuzve = implode(',', $xref_list);

    $letrehoz = mysql_query('UPDATE wormsignh_haffner.gigatel SET compatible_devices_new="'
            . addslashes($osszefuzve) . '" WHERE cikkszam="' . addslashes($row['cikkszam']) . '"');
    if (!$letrehoz) {
        die('Could not connect: ' . mysql_error());
    }
}

echo $letrehoz . '<br>';
echo 'xref to compatible_devices';

// kiválasztja azokat a típusokat aminek van megfeleltetése a convert táblában
// 
//SELECT * FROM `haffner_xref` x LEFT JOIN xref_convert xc ON (x.xref=xc.xref) WHERE NOT ISNULL(xc.xref) GROUP BY x.xref


// kiválasztja azokat a típusokat aminek NINCS megfeleltetése a convert táblában
//SELECT * FROM `haffner_xref` x LEFT JOIN xref_convert xc ON (x.xref=xc.xref) 
//WHERE NOT ISNULL(xc.xref) GROUP BY x.xref

// haffnew_xref aktualizálása
//UPDATE `haffner_xref` x
//INNER JOIN xref_convert xc ON (x.xref=xc.xref)
//SET x.xref_new = xc.xref_new