<?php

set_time_limit(1200);

header('Content-Type: text/html; charset=utf-8');
require_once 'lib/init.php';

//require_once 'keszlet2.php';


ata_mysql_query("set names 'utf8'", $connection);

$result = ata_mysql_query("
  INSERT IGNORE INTO wormsignh_wormsign_hu.tps_webshop
  (haffner_id, cameron_sku, haffner_category, beszerar, ar)
  SELECT id, sku, categories, price, ws_kisker FROM wormsignh_haffner.products
  ");


echo 'Új termékek átmásolva tps_webshopba' . '<br/>';

//átmásoljuk a készletet haffner_xrefbe

$result = ata_mysql_query("UPDATE wormsignh_haffner.haffner_xref pp
  INNER JOIN wormsignh_wormsign_hu.tps_webshop w ON (w.cameron_sku = pp.sku)
  SET pp.stock=w.keszlet_new
  ");

echo 'Készlet másolása xref-be <br/>';


//$result = ata_mysql_query('SELECT sku FROM wormsignh_haffner.products WHERE stock>=3');
//while ($row = mysql_fetch_assoc($result)) {
//    $xrefSql = 'SELECT x2.szla_id FROM wormsignh_haffner.haffner_xref x1'
//        . ' INNER JOIN wormsignh_haffner.haffner_xref x2 ON (x1.xref=x2.xref AND x1.szla_id != x2.szla_id)'
//        . ' WHERE x1.sku="' . addslashes($row['sku']) . '" AND x2.stock > 2';
//    $result1 = ata_mysql_query($xrefSql);
//    
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


//
//$result = ata_mysql_query("
//  UPDATE wormsignh_haffner.products pp
//  INNER JOIN wormsignh_wormsign_hu.tps_webshop w ON (pp.sku=w.cameron_sku)
//  SET pp.ws_kisker = w.ar
//  ");
//
//
//echo 'MEglevő kisker árak bemásolása' . '<br/>';



$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price/0.7,
ws_kisker_uj=CONCAT(LEFT(ws_kisker_uj, LENGTH(ws_kisker_uj)-2), '90')
  ");


echo 'Kisker ár generálása' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*5 WHERE price <200
  ");


echo 'Kisker ár generálása ha olcsobb a beszer ára mint 200Ft.(5)' . '<br/>';


$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*15 WHERE price <100
  ");


echo 'Kisker ár generálása ha olcsobb a beszer ára mint 100Ft.(15x)' . '<br/>';


$result = ata_mysql_query("
  update wormsignh_haffner.products
set publish=0 WHERE ws_kisker_uj<100
  ");


echo '100Ftnál olcsobb kisker árú termékek inaktiválása.' . '<br/>';



$result = ata_mysql_query("
  update wormsignh_wormsign_hu.tps_webshop
SET akcios_brutto_ar=ar*0.92,
    akcios_netto_ar=akcios_brutto_ar/1.27,
    akcio_kezdete='2015-05-29'
    WHERE keszlet_new>=5 AND haffner_id!=0
  ");


echo 'Akciós árak generálása' . '<br/>';



$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET export=1 WHERE keszlet_new>0
  ");


echo 'export generálás <br/>';


$leiras = "CONCAT(IF(''=w.compatible_devices,w.description, CONCAT(w.description,'</br>Kompatibilis típusok:  ',w.compatible_devices)))";


$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
  INNER JOIN wormsignh_haffner.products w ON (w.id = pp.haffner_id)
  SET pp.export=w.export, pp.ar=w.ws_kisker_uj, pp.megnevezes=w.short_description,
  pp.leiras_hosszu=$leiras, pp.hasonlo_termekek=w.new_related, pp.image_url= w.image_url,
  pp.active= w.publish, pp.szla_id=pp.id, pp.vasarolhato_ha_nincs_raktaron='0',pp.kiegeszito_termekek=w.kiegeszitok,pp.CrossSale1='1',pp.CrossSale2='1',pp.CrossSale3='1',pp.UpSale1='1',pp.UpSale2='1'
  ");




echo 'Termék adatok átmásolva <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET hasonlo_termekek='' WHERE hasonlo_termekek=0
  ");


echo 'Termék adatok átmásolva hasonló termékek <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET elerhetoseg = IF(keszlet>0, '3 munkanap', '')
  ");


echo 'Elérhetőség 3 munkanap <br/>';



//$result = ata_mysql_query("
//  UPDATE wormsignh_haffner.products
//  SET compatible_devices=SUBSTR(compatible_devices FROM 3)
//  ");
//
//
//echo 'Első karakterek törlése' . '<br/>';
//
//
//
//$result = ata_mysql_query("
//  UPDATE wormsignh_haffner.products
//  SET compatible_devices = REPLACE(compatible_devices, (' * '), ', '),
//  compatible_devices = REPLACE(compatible_devices, ('     * '), ', ')
//  ");
//
//echo 'Kompatibilitás frissítése <br/>';



$result = ata_mysql_query("
  SELECT youtube FROM wormsignh_haffner.products WHERE youtube!=''
  ");
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

$row = mysql_fetch_assoc($result);
$youtube_link= $row['youtube'];


$youtube = '<iframe width="560" height="315" src="' . "$youtube_link" . '"frameborder="0" allowfullscreen></iframe>';
$youtube = addslashes($youtube);
$sql = "UPDATE wormsignh_wormsign_hu.tps_webshop pp
  INNER JOIN wormsignh_haffner.products w ON (w.id = pp.haffner_id)
  SET pp.leiras_hosszu=IF(w.youtube!='',CONCAT(w.description,'</br>', '$youtube'),w.description)
  ";

$result = ata_mysql_query($sql);
if (!$result) {
    die('Invalid query: ' . mysql_error());
}




//update products
//set ws_kisker_uj=greatest((price)/0.7,ws_kisker*1.08)
//UPDATE products SET ws_kisker_uj=CONCAT(LEFT(ws_kisker_uj, LENGTH(ws_kisker_uj)-2), '90')
//update wormsignh_wormsign_hu.tps_webshop pp
//inner join wormsignh_haffner.products w on(w.sku=pp.cameron_sku)
//set pp.category=w.category

//átmásoljuk a szla_id-t haffner xref-be

$result = ata_mysql_query("UPDATE wormsignh_haffner.haffner_xref pp
  INNER JOIN wormsignh_wormsign_hu.tps_webshop w ON (w.cameron_sku = pp.sku)
  SET pp.szla_id=w.szla_id
  ");
echo 'SZLA-ID másolása xref-be <br/>';


//kiegészítő termékeket ajánlot products táblába, 
//az xref tábla alapján, hozzárendeljük véletlenszerűen az 5 db kiegészítő terméket




echo 'kész';