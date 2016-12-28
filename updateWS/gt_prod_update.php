
<?php

SET_time_limit(1200);

//header('Content-Type: text/html; charSET=utf-8');
require_once 'lib/init.php';

//require 'gigatel_update_ts.php';

//gt_xref_update-et is le kell futtatni néha

ata_mysql_query("SET names 'utf8'", $connection);




//kategoriák összemásolása alt_cat ba products táblában


$result = ata_mysql_query("
  update wormsignh_haffner.gigatel_params set alt_cat = if(alt_cat like concat('%', full_subcategory1, '%'),
 alt_cat, concat(alt_cat, IF(alt_cat!='', '@', ''), full_subcategory1))
  ");
//
//$result = ata_mysql_query("
//update wormsignh_haffner.products set alt_cat = if(alt_cat like concat('%', new_alternative, '%'),
// alt_cat, concat(alt_cat, IF(alt_cat!='', '@', ''), new_alternative))
//  ");




$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
INNER JOIN wormsignh_haffner.gigatel w ON(pp.cameron_sku = w.sku)
SET pp.ar = w.brutto_kisker_ar, pp.haffner_category = w.new_main_category,
pp.marka=w.gyarto,pp.alt_cat=w.alt_cat,pp.kiegeszito_termekek=w.kiegeszitok
  ");

echo 'tps_webshop frissítése gigatel db-ből' . '<br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
INNER JOIN wormsignh_haffner.gigatel w ON(pp.cameron_sku = w.sku)
SET pp.megnevezes=IF(w.ts_name!='',w.ts_name,w.cikknev)
  ");

echo 'tps_webshop frissítése gigatel db-ből tokshop nevek!!' . '<br/>';

//gt_tokshopból paraméterek átmásolása tps_webshopba

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
INNER JOIN wormsignh_haffner.gt_tokshop w ON(pp.gigatel_id = w.gt_sku)
SET pp.szin=w.szin,pp.kivitel=w.kivitel,pp.eredetiseg=w.eredetiseg,
pp.kiszereles=w.kiszereles,pp.vizallosag=w.vizallosag,
  pp.vedelem=w.vedelem,pp.anyag=w.anyag,pp.meret=w.meret,
  pp.kiegeszito_jellege=w.kiegeszito_jellege,pp.kivitel=w.kivitel,
  pp.compatible_device=w.compatible_device,pp.noi=w.noi,pp.ferfi=w.ferfi
  ");

echo 'tps_webshop frissítése gt_tokshop db-ből' . '<br/>';


//pp.alt_cat= IF(w.alt_cat != '', CONCAT('KERESO|', w.alt_cat),'')


//tps_webshopba leírás generálása leírás és kompatibilitás alapján ha a compatible_devices_new-ban van érték
$result = ata_mysql_query("SELECT w.cikkszam, w.compatible_devices_new, w.termekinfo FROM wormsignh_haffner.gigatel w");
while ($row = mysql_fetch_assoc($result)) {
    $cd = $row['compatible_devices_new'];
    $tmp = explode(',', $cd);
    $as = array();
    foreach ($tmp as $name) {
        if ($name) {
            $url = 'shop_search.php?complex=ok&search=' . urlencode($name) . '&type=2&subcat=0&in_what=00010000';
            $as[] = '<a href="' . htmlentities($url) . '">' . htmlentities($name) . '</a>';
        }
    }
    $desc = $row['termekinfo'] . '</br>További készülék típusok: ' . implode(', ', $as);
    $desc = strtr($desc, array("\r" => ' ', "\n" => ' '));

    ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop SET leiras_hosszu=IF('" . addslashes($row['compatible_devices_new']) . "'!='','" . addslashes($desc) . "','" . addslashes($row['termekinfo']) . "') "
            . " WHERE gigatel_id = '" . addslashes($row['cikkszam']) . "'");
}

echo 'Termék leírás generálás <br/>';


//tps_webshopba leírás generálása leírás és kompatibilitás alapján, ha a compatible_devices_new-ban nincs érték
$result = ata_mysql_query("SELECT w.cikkszam, w.compatible_devices, w.termekinfo FROM wormsignh_haffner.gigatel w");
while ($row = mysql_fetch_assoc($result)) {
    $cd = $row['compatible_devices'];
    $tmp = explode(',', $cd);
    $as = array();
    foreach ($tmp as $name) {
        if ($name) {
            $url = 'shop_search.php?complex=ok&search=' . urlencode($name) . '&type=2&subcat=0&in_what=00010000';
            $as[] = '<a href="' . htmlentities($url) . '">' . htmlentities($name) . '</a>';
        }
    }
    $desc = $row['termekinfo'] . '</br>További készülék típusok: ' . implode(', ', $as);
    $desc = strtr($desc, array("\r" => ' ', "\n" => ' '));

    ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop SET leiras_hosszu=IF('" . addslashes($row['compatible_devices_new']) . "'='','" . addslashes($desc) . "','" . addslashes($row['termekinfo']) . "') "
            . " WHERE gigatel_id = '" . addslashes($row['cikkszam']) . "'");
}

echo 'Termék leírás generálás <br/>';


$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET szla_id=id WHERE szla_id=''
  ");
echo 'SZLA-ID másolása xref-be <br/>';

//gigatel_xref frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel_xref pp
  INNER JOIN wormsignh_wormsign_hu.tps_webshop w ON (w.gigatel_id = pp.cikkszam)
  SET pp.szla_id=w.szla_id,pp.name=w.megnevezes, pp.category=w.haffner_category
  ");
echo 'SZLA-ID másolása xref-be <br/>';



/*

$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel_xref pp
  INNER JOIN wormsignh_haffner.gigatel w ON (w.cikkszam = pp.cikkszam)
  SET pp.sku=w.sku
  ");
echo 'SZLA-ID másolása xref-be <br/>';

echo 'gigatel_xref szla_id-k frissítése' . '<br/>';
*/
//$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel
//SET new_main_category=CONCAT(uj_kategoria, '|', new_subcategory, '|', cat_brand)
//WHERE uj_kategoria LIKE '%akkumulátor%'
//  ");

$result = ata_mysql_query("
  update wormsignh_haffner.gigatel
set haszonkulcs=100-(dev_netto/netto_kisker_ar)*100
  ");
echo 'gigatelben haszonkulcs számítása' . '<br/>';

//require 'gt_xref_update.php';



/*
//xref átmásolása product_xref-be haffnerből
$result = ata_mysql_query("insert ignore into wormsignh_haffner.product_xref
(id,sku,cikkszam,xref,xref_new,szla_id,stock,name,category,letrehozva,haszonkulcs)
SELECT id,sku,cikkszam,xref,xref_new,szla_id,stock,name,category,letrehozva,haszonkulcs
FROM wormsignh_haffner.haffner_xref WHERE szla_id!=''
  ");

//xref átmásolása product_xref-be gigatelből
$result = ata_mysql_query("insert ignore into wormsignh_haffner.product_xref
(id,sku,cikkszam,xref,xref_new,szla_id,stock,name,category,letrehozva,haszonkulcs)
SELECT id,sku,cikkszam,xref,xref_new,szla_id,stock,name,category,letrehozva,haszonkulcs
FROM wormsignh_haffner.gigatel_xref WHERE szla_id!=''
  ");

//product_xref frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.product_xref pp
    INNER JOIN wormsignh_haffner.gigatel_xref w ON(w.sku=pp.sku)
    set pp.xref_new=w.xref_new,pp.stock=w.stock
  ");

//product_xref frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.product_xref pp
    INNER JOIN wormsignh_haffner.haffner_xref w ON(w.sku=pp.sku)
    set pp.xref_new=w.xref_new,pp.stock=w.stock
  ");
*/
