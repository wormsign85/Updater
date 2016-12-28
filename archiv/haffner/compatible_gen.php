<?php

// ez másolja be a compatible_devies-ból kiszedett kompatibilis típusokat haffner_xref-be
//ez után visszamásoljuk a letisztitott xref-eket products táblába  
//(xref_make_new_compatible.php)
//
//repair_compatibles.php javítja az elírásokat stb.
//
//majd onnan bemásoljuk az alternativ kategóriákat productsba és tps_webshopba.
//(tok_alt_cat.php,hatlap_alt_cat.php,folia_alt_cat.php stb)

set_time_limit(6000);

require_once 'lib/init.php';
//require_once 'actions.php';

try {
    $conn = new PDO($config_db['connection'], $config_db['username'], $config_db['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}


mb_internal_encoding("UTF-8");

$sql_delete_haffner_xref = "DELETE FROM wormsignh_haffner.haffner_xref";
$q = $conn->prepare($sql_delete_haffner_xref);
$q->execute();

echo 'Haffner_xref törölve';



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
        $letrehoz = mysql_query('REPLACE INTO wormsignh_haffner.haffner_xref '
                . 'SET sku="' . addslashes($row['sku']) . '", cikkszam="' . addslashes($row['id']) . '"'
                . ', xref="' . addslashes($termek) . '" '
                . ', xref_new="' . addslashes($termek) . '" '
                . ', letrehozva= NOW()');
        if (!$letrehoz) {
            die('Could not connect: ' . mysql_error());
        }
    }
}

echo 'Töröltük a haffner_xref táblát, majd legeneráltuk újból!<br>';


$result = ata_mysql_query("UPDATE wormsignh_haffner.haffner_xref pp
  INNER JOIN wormsignh_wormsign_hu.tps_webshop w ON (w.cameron_sku = pp.sku)
  SET pp.szla_id=w.szla_id,pp.name=w.megnevezes, pp.category=w.haffner_category, pp.haszonkulcs=w.haszonkulcs
  ");


echo 'haffner_xref-be bemásoltuk tps_webshopból a szla_id-t, a nevet, kategóriát, haszonkulcsot.<br/>';


echo 'Most futtasd le az xref_make_new_compatible.php-t, hogy bekerüljenek a javított xref-ek </br>'
. ' a products / compatbile_devices_new mezőbe'
. 'Majd futtasd le a tok, hátlap és fólia alternativ kategória generátorokat';

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
//require 'repair_compatibles.php';
//require 'xref_make_new_compatible.php';
//require 'szetbont_params.php';

//xref átmásolása product_xref-be haffnerből
//$result = ata_mysql_query("insert ignore into wormsignh_haffner.product_xref
//(id,sku,xref,xref_new,szla_id,stock,name,category,letrehozva,haszonkulcs)
//SELECT id,sku,xref,xref_new,szla_id,stock,name,category,letrehozva,haszonkulcs
//FROM wormsignh_haffner.haffner_xref
//  ");
