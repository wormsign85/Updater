<?php

set_time_limit(6000);

require_once 'lib/init.php';
//require_once 'lib/actions.php';
require_once 'lib/szetbont_nev_params.php';

ata_mysql_query("set names 'utf8'", $connection);

$map = array();
$result = mysql_query("SELECT sku, short_description, uj_nev FROM wormsignh_haffner.products");
while ($row = mysql_fetch_assoc($result)) {
    $oszlopok = szetbontTulajdonsagok($row['short_description']);
    if ($oszlopok) {
        $sqlOszlopok = array();
        foreach ($oszlopok as $oszlop => $ertek) {
            $sqlOszlopok[] = $oszlop . '=' . '"' . addslashes($ertek) . '"';
        }

        $setek = implode(',', $sqlOszlopok);
        $letrehoz = mysql_query('UPDATE wormsignh_haffner.products SET ' . $setek . ' WHERE sku="' . addslashes($row['sku']) . '"');
        if (!$letrehoz) {
            die('Could not connect: ' . mysql_error());
        }
    }
}
echo 'Paraméterek kiírva HAFFNER db eredeti nevek';

$map = array();
$result = mysql_query("SELECT sku, short_description, uj_nev FROM wormsignh_haffner.products");
while ($row = mysql_fetch_assoc($result)) {
    $oszlopok = szetbontTulajdonsagok($row['uj_nev']);
    if ($oszlopok) {
        $sqlOszlopok = array();
        foreach ($oszlopok as $oszlop => $ertek) {
            $sqlOszlopok[] = $oszlop . '=' . '"' . addslashes($ertek) . '"';
        }

        $setek = implode(',', $sqlOszlopok);
        $letrehoz = mysql_query('UPDATE wormsignh_haffner.products SET ' . $setek . ' WHERE sku="' . addslashes($row['sku']) . '"');
        if (!$letrehoz) {
            die('Could not connect: ' . mysql_error());
        }
    }
}
echo 'Paraméterek kiírva HAFFNER db új nevek';
//
//$map = array();
//$result = mysql_query("SELECT sku, cikknev FROM wormsignh_haffner.gigatel");
//while ($row = mysql_fetch_assoc($result)) {
//    $oszlopok = szetbontTulajdonsagok($row['cikknev']);
//    if ($oszlopok) {
//        $sqlOszlopok = array();
//        foreach ($oszlopok as $oszlop => $ertek) {
//            $sqlOszlopok[] = $oszlop . '=' . '"' . addslashes($ertek) . '"';
//        }
//
//        $setek = implode(',', $sqlOszlopok);
//        $letrehoz = mysql_query('UPDATE wormsignh_haffner.gigatel_params SET ' . $setek . ' WHERE sku="' . addslashes($row['sku']) . '"');
//        if (!$letrehoz) {
//            die('Could not connect: ' . mysql_error());
//        }
//    }
//}
//echo 'Paraméterek kiírva gigatel db';


$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
  INNER JOIN wormsignh_haffner.products w ON (w.id = pp.haffner_id)
  SET pp.szin_rgb=w.szin_rgb,pp.folia_tipus=w.folia_tipus,pp.lathatosag=w.lathatosag,
  pp.csomagolas=w.csomagolas,pp.kivitel=w.kivitel,pp.eredetiseg=w.eredetiseg,
  pp.kiszereles=w.kiszereles,pp.vizallosag=w.vizallosag,pp.vedelem=w.vedelem,
  pp.anyag=w.anyag,pp.meret=w.meret,pp.kiegeszito_jellege=w.kiegeszito_jellege, pp.compatible_device = w.compatible_device
  ");

echo 'paraméterek átmásolva tps_webshopba';