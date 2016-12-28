<?php

set_time_limit(1200);

header('Content-Type: text/html; charset=utf-8');
require_once 'lib/init.php';

//require_once 'keszlet2.php';
//uj_kategoria elé MOBIL_ fűzése
//update products
//set uj_kategoria=CONCAT('MOBIL_',uj_kategoria) 
//        WHERE uj_kategoria NOT LIKE '%MOBIL_%' AND uj_kategoria !=''

ata_mysql_query("set names 'utf8'", $connection);

//INSERT IGNORE INTO wormsignh_haffner.uj_kategoriak
//  (sku, kategoria)
//  SELECT sku, uj_kategoria FROM wormsignh_haffner.products WHERE uj_kategoria!='' AND export=1
//  $result = ata_mysql_query("UPDATE wormsignh_haffner.uj_kategoriak pp
//  INNER JOIN wormsignh_haffner.products w ON (w.sku = pp.sku)
//  SET pp.kategoria=w.categories
//  ");
//
//  echo 'Készlet másolása xref-be <br/>';

//$result = ata_mysql_query("SELECT w.cameron_sku, w.compatible_devices_new, w.leiras_hosszu FROM wormsignh_wormtest.tps_webshop_feltolt w");
//while ($row = mysql_fetch_assoc($result)) {
//    $cd = $row['compatible_devices_new'];
//    $tmp = explode(',', $cd);
//    $as = array();
//    foreach ($tmp as $name) {
//        if ($name) {
//            $url = 'shop_search.php?complex=ok&search=' . urlencode($name) . '&type=2&subcat=0&in_what=10110001';
//            $as[] = '<a href="' . htmlentities($url) . '">' . htmlentities($name) . '</a>';
//        }
//    }
//
//    $desc = '</br>Kompatibilis készülék típusok: <div class="xref-list">' . implode(' ', $as) . '</div>';
//    $desc = strtr($desc, array("\r" => ' ', "\n" => ' '));
//
//    $sqlbeDesc = '';
//    if ($row['compatible_devices_new']) {
//        $sqlbeDesc .= $desc;
//    }
//
//
////    $sqlbeDesc = $row['description'] . $desc;
//    ata_mysql_query("UPDATE wormsignh_wormtest.tps_webshop_feltolt SET "
//            . "compatible_desc='" . addslashes($sqlbeDesc) . "'"
//            . " WHERE cameron_sku = '" . addslashes($row['cameron_sku']) . "'");
//}
//
//echo 'Működj </br>';
//
//$result = ata_mysql_query("UPDATE wormsignh_wormtest.tps_webshop pp
//  INNER JOIN wormsignh_wormtest.tps_webshop_feltolt w ON (w.cameron_sku = pp.cameron_sku)
//  SET pp.compatible_desc=w.compatible_desc
//  ");


$result = ata_mysql_query("
  INSERT IGNORE INTO wormsignh_wormsign_hu.tps_webshop
  (haffner_id, cameron_sku, haffner_cat_original, haffner_category, beszerar, ar,letrehozva)
  SELECT id, sku, categories,categories, price, ws_kisker,letrehozva FROM wormsignh_haffner.products
  ");


echo 'Új termékek átmásolva tps_webshopba' . '<br/>';

//    $result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
//  INNER JOIN wormsignh_haffner.products w ON (pp.cameron_sku = w.sku)
//  SET pp.haffner_category=w.mobile_category WHERE w.mobile_category!=''
//  ");
//
//  echo 'MOBIL_kategoriák másolása tps_webshopba <br/>';
//  $result = ata_mysql_query("update wormsignh_haffner.products
//set mobile_category=uj_kategoria WHERE uj_kategoria LIKE '%Telefon Tokok%' 
//OR uj_kategoria LIKE '%hátlapok%' OR uj_kategoria LIKE '%fóliák%'
//");
//
//echo 'MOBIL_ előtagos átnevezett kategok másolása mobile_category mezőbe<br>';
//átmásoljuk a készletet haffner_xrefbe

$result = ata_mysql_query("UPDATE wormsignh_haffner.haffner_xref pp
  INNER JOIN wormsignh_wormsign_hu.tps_webshop w ON (w.cameron_sku = pp.sku)
  SET pp.stock=w.keszlet_new
  ");

echo 'Készlet másolása xref-be <br/>';


$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price/0.5
  ");


echo 'Kisker ár generálása' . '<br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
    SET keszlet_new='4',
    keszlet='4'
    WHERE gigatel_stock='készleten' and gigatel_id!=''
       ");

echo 'keszlet feltöltes gigatel ami készletenvan az 4 db <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
    SET keszlet_new='0',
    keszlet='0'
    WHERE gigatel_stock NOT LIKE '%készleten%' and gigatel_id!=''
       ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
    SET export=IF(keszlet>0,'1','0') WHERE (haffner_id!='' OR gigatel_id!='')
       ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
    SET export=0 WHERE (haffner_id!='' OR gigatel_id!='') AND megnevezes =''
       ");


echo 'export státusz = 0 <br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*3
 WHERE categories LIKE '%Slim Flexi Flip%'
  ");

echo 'Kisker ár generálása Slim flexi flip tokok' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*2.1
 WHERE categories LIKE '%Képernyővédő fóliák és üvegek%' AND manufacturer='EazyGuard'
  ");

echo 'Kisker ár generálása fóliák és üvegek, eazyguard' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*2.9
 WHERE categories LIKE '%S-View Flip Cover tokok%' AND manufacturer='Eazy Case'
  ");

echo 'S-View Flip Cover tokok, eazy case' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*2.9
 WHERE manufacturer LIKE 'Eazy Case'
  ");

echo 'Eazy Case' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price/0.6
 WHERE categories LIKE '%töltők%'
  ");

echo 'noname töltők' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*4
 WHERE categories LIKE '%Tokok és táskák|Fitness/Sport tokok%'
  ");

echo 'Tokok és táskák >Fitness/Sport tokok' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*2.5
 WHERE categories LIKE '%Tokok és táskák|Fitness/Sport tokok%' AND manufacturer = 'SOX'
  ");

echo 'Tokok és táskák >Fitness/Sport tokok *1.8' . '<br/>';


$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*2.5
 WHERE categories LIKE '%Style Slim tokok%' AND manufacturer='Eazy Case'
  ");

echo 'Style Slim tokok Eazy case *1.7' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price/0.4
 WHERE manufacturer='Pierre Cardin'
  ");
echo 'Pierre tokok  /0.44' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*3
 WHERE categories LIKE '%fóliák%' AND manufacturer='Cameron Sino'
  ");

echo 'Cameron fóliák *3' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*3
 WHERE categories LIKE '%tokok%' AND manufacturer='Haffner'
  ");

echo 'Haffner tokok *2.5' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price/0.4
 WHERE categories LIKE '%vezetékes headsetek%' AND manufacturer='Muvit'
  ");

echo 'Muvit headsetek 45%' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*4
 WHERE categories LIKE '%telefon hátlapok%' AND manufacturer='Haffner'
  ");

echo 'Haffner telefon tálapok *4' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*2.5
 WHERE categories LIKE '%iPhone 3/4/5/6 tartozékok|Telefon hátlapok|iPhone 6%'
  ");

echo 'iPhone 3/4/5/6 tartozékok|Telefon hátlapok|iPhone 6' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*14
 WHERE categories LIKE '%Képernyővédő fóliák és üvegek|%' AND price<150
  ");

echo 'Képernyővédő fóliák és üvegek| WHERE price<150' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*15 WHERE price <200
  ");

echo 'Kisker ár generálása ha olcsobb a beszer ára mint 200Ft.(5)' . '<br/>';


$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=price*30 WHERE price <100
  ");


echo 'Kisker ár generálása ha olcsobb a beszer ára mint 100Ft.(15x)' . '<br/>';


$result = ata_mysql_query("
  update wormsignh_haffner.products
set publish=0 WHERE ws_kisker_uj<100
  ");


echo '100Ftnál olcsobb kisker árú termékek inaktiválása.' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set publish=0 WHERE stock=0
  ");


echo 'Készlet nélküli termékek aktiválása' . '<br/>';


$result = ata_mysql_query("
  update wormsignh_haffner.tokshop_arak pp
  inner join wormsignh_haffner.products w ON(pp.sku=w.sku)
set pp.beszer=w.price
  ");

echo 'Beszer ár generálás tokshop' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.tokshop_arak
set haszonkulcs=100-(beszer/kisker)*100
  ");

echo 'Haszonkulcs generálás tokshop' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.tokshop_arak
set haszonkulcs='' WHERE beszer = ''
  ");

echo 'Haszonkulcs generálás tokshop' . '<br/>';

//tokshop árak átmásolása!!
$result = ata_mysql_query("
  update wormsignh_haffner.products pp
  inner join wormsignh_haffner.tokshop_arak w ON(pp.sku=w.sku)
set pp.ws_kisker_uj=w.kisker WHERE w.haszonkulcs>10
  ");


echo '!!! Tokshop !!! Kisker ár generálása' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set haszonkulcs=100-(price/ws_kisker_uj)*100
  ");

echo 'Haszonkulcs generálás' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_haffner.products
set ws_kisker_uj=CONCAT(LEFT(ws_kisker_uj, LENGTH(ws_kisker_uj)-2), '89')
  ");


echo 'Árak kerekítés 90 végűre' . '<br/>';


//$result = ata_mysql_query("
//  update wormsignh_haffner.haffner_xref pp
//  inner join wormsignh_haffner.products w ON(pp.sku=w.sku)
//set pp.haszonkulcs=w.haszonkulcs
//  ");
//
//
//echo 'Haszonkulcs másolás' . '<br/>';

$result = ata_mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set akcios_brutto_ar = NULL,
akcios_netto_ar = NULL,
akcio_kezdete = NULL,
akcio_lejarat = NULL
  ");

echo 'akcioók nullázása <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
  INNER JOIN wormsignh_haffner.products w ON (w.id = pp.haffner_id)
  SET pp.ar=w.ws_kisker_uj
  ");
echo 'Termék adatok átmásolva <br/>';

$akcio_ha = 'keszlet_new>=10';
$kedvezmeny = 'ar*0.9';
$netto_ar = 'akcios_brutto_ar/1.27';
$akcio_kezdet = '2016-01-10';

$result = ata_mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set akcios_brutto_ar = IF($akcio_ha, $kedvezmeny,'')
            WHERE haszonkulcs>50
  ");


$result = ata_mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set akcios_brutto_ar = IF(megnevezes LIKE '%Galaxy S%' 
OR megnevezes LIKE '%Galaxy Note%' 
OR megnevezes LIKE '%Galaxy Ace%' 
OR megnevezes LIKE '%Grand Prime%',ar*0.88,'')
where haszonkulcs>=40
  ");



$result = ata_mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set akcios_brutto_ar = IF(megnevezes LIKE '%Galaxy S%' 
OR megnevezes LIKE '%Galaxy Note%' 
OR megnevezes LIKE '%Galaxy Ace%' 
OR megnevezes LIKE '%Grand Prime%',akcio_kezdete='2016-01-07','')
where haszonkulcs>=40
  ");



$result = ata_mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set akcios_brutto_ar = IF(megnevezes LIKE '%Galaxy S%' 
OR megnevezes LIKE '%Galaxy Note%' 
OR megnevezes LIKE '%Galaxy Ace%' 
OR megnevezes LIKE '%Grand Prime%',akcio_lejarat='2016-01-31','')
where haszonkulcs>=40
  ");

echo 'Akcios Samsungok széria 12% ha 40%nál nagyobb az árrés<br/>';





//$result = ata_mysql_query("
//update wormsignh_wormsign_hu.tps_webshop
//SET akcios_netto_ar = akcios_brutto_ar/1.27,
//        akcio_kezdete = '2016-01-10',
//        akcio_lejarat = '2016-01-30'
//            WHERE akcios_brutto_ar!=''
//  ");


$result = ata_mysql_query("
  update wormsignh_wormsign_hu.tps_webshop
set akcios_brutto_ar=CONCAT(LEFT(akcios_brutto_ar, LENGTH(akcios_brutto_ar)-2), '89')
WHERE akcios_brutto_ar!='' AND ar>2000
  ");


echo 'Akcios Árak kerekítés 90 végűre' . '<br/>';

$result = ata_mysql_query("
  update wormsignh_wormsign_hu.tps_webshop
set akcios_haszonkulcs=100-(beszerar/akcios_brutto_ar)*100
  ");

echo 'Haszonkulcs generálás' . '<br/>';




$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET export=IF(haffner_cat_original='','0','1') WHERE haffner_id!=''
  ");

echo 'export generálás ha nincs eredeti haffner kategoria <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET export=IF(keszlet_new>0,'1','0') WHERE haffner_id!='' OR gigatel_id!=''
  ");

echo 'export generálás <br/>';



$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET active=IF(keszlet_new>0,'1','0') WHERE haffner_id!='' OR gigatel_id!=''
  ");

echo 'Aktiválás készleten levők <br/>';


$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET active=1 WHERE gigatel_id!=''
  ");

echo 'Gigateles termékek aktiválása <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET erdeklodjon=IF(keszlet=0,'1','0')
   WHERE gigatel_id!=''
  ");

echo 'Gigteles termékek éreklődjön státuszra állítása ha nincs készlet';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET export=1
   WHERE gigatel_id!=''
  ");


//$result = ata_mysql_query("update wormsignh_wormsign_hu.tps_webshop
//set active=1
//where haffner_category like '%MOBIL_Akkumulátorok%'
//  ");



$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
  INNER JOIN wormsignh_haffner.products w ON (w.id = pp.haffner_id)
  SET pp.letrehozva=w.letrehozva
  ");

echo 'Dátum átmásolva <br/>';


$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET active=IF(letrehozva>'" . date('Y-m-d H:i:s', time() - 30 * 24 * 3600) . "','2','1')
      WHERE (haffner_id!='' OR gigatel_id!='') AND keszlet_new>0
  ");

echo 'Új termékek aktiválása <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET active=0,
   keszlet_new=0,
   keszlet=0,
   gigatel_stock='Kifutó'
  WHERE keszletdate_new<'" . date('Y-m-d H:i:s', time() - 1 * 24 * 3600) . "'
      AND gigatel_id!=''
  ");

echo 'Gigatel kifutók leállítása <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET active=0,
   keszlet_new=0,
   keszlet=0
  WHERE keszletdate_new<'" . date('Y-m-d H:i:s', time() - 1 * 24 * 3600) . "'
      AND haffner_id!=''
  ");

echo 'Haffner kifutók leállítása <br/>';



/*
  $result = ata_mysql_query("UPDATE products
  SET categories ='Telefon Tokok|Lenyitható Tokok|Samsung'
  WHERE categories = 'Tokok és táskák|Slim Flexi Flip|Samsung'
  ");
  $result = ata_mysql_query("UPDATE products
  SET categories ='Telefon tokok|Lenyitható Tokok|Case-Mate Tokok'
  WHERE categories = 'Tokok és táskák|Case-Mate tokok' AND short_description LIKE '%Case-Mate Slim Flip%'
  ");

  echo 'Főkategoriák átnevezése<br>';
 */


//kategoriák összemásolása alt_cat ba products táblában


$result = ata_mysql_query("
  update wormsignh_haffner.products set alt_cat = if(alt_cat like concat('%', uj_kategoria, '%'),
 alt_cat, concat(alt_cat, IF(alt_cat!='', '@', ''), uj_kategoria))
  ");

$result = ata_mysql_query("
update wormsignh_haffner.products set alt_cat = if(alt_cat like concat('%', new_alternative, '%'),
 alt_cat, concat(alt_cat, IF(alt_cat!='', '@', ''), new_alternative))
  ");


$leiras = "CONCAT(IF(''=w.compatible_devices_new,w.description, CONCAT(w.description,'</br>Kompatibilis típusok:  ',w.compatible_devices_new)))";
$alter_cat = "CONCAT(IF(''=w.alternative_category,'', CONCAT(w.alternative_category,'@',w.alt_cat)))";

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
  INNER JOIN wormsignh_haffner.products w ON (w.id = pp.haffner_id)
  SET pp.export=w.export,pp.haszonkulcs=w.haszonkulcs,pp.marka=w.manufacturer,pp.alt_cat=w.alt_cat, 
  pp.ar=w.ws_kisker_uj, pp.megnevezes=IF(w.uj_nev!='',w.uj_nev,w.short_description),
  pp.hasonlo_termekek=w.new_related, pp.compatible_device= w.compatible_device, 
  pp.image_url= w.image_url, pp.szla_id=pp.id, pp.vasarolhato_ha_nincs_raktaron='0',
  pp.kiegeszito_termekek=w.kiegeszitok,pp.CrossSale1='1',pp.CrossSale2='1',
  pp.CrossSale3='1',pp.UpSale1='1',pp.UpSale2='1',pp.szin_rgb=w.szin_rgb,
  pp.kivitel=w.kivitel,pp.eredetiseg=w.eredetiseg,pp.kiszereles=w.kiszereles,pp.letrehozva=w.letrehozva,
  pp.vizallosag=w.vizallosag,pp.ferfi=w.ferfi, pp.noi=w.noi, pp.tartozek=w.tartozek,pp.vedelem=w.vedelem,pp.anyag=w.anyag,pp.meret=w.meret,pp.haffner_cat_original=w.categories
  ");

//$result = ata_mysql_query("SELECT w.sku, w.compatible_devices_new, w.description FROM wormsignh_haffner.products w");
//while ($row = mysql_fetch_assoc($result)) {
//    $cd = $row['compatible_devices_new'];
//    $tmp = explode(',', $cd);
//    $as = array();
//    foreach ($tmp as $name) {
//        if ($name) {
//            $url = 'shop_search.php?complex=ok&search=' . urlencode($name) . '&type=2&subcat=0&in_what=00000001';
//            $as[] = '<a href="' . htmlentities($url) . '">' . htmlentities($name) . '</a>';
//        }
//    }
//
//
//
//    $desc = $row['description'] . '</br>Kompatibilis típusok: ' . implode(', ', $as);
//    $desc = strtr($desc, array("\r" => ' ', "\n" => ' '));
//
//    ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop SET leiras_hosszu=IF('" . addslashes($row['compatible_devices_new']) . "'!='','" . addslashes($desc) . "','" . addslashes($row['description']) . "') "
//            . " WHERE cameron_sku = '" . addslashes($row['sku']) . "'");
//}
//
//echo 'Termék adatok átmásolva <br/>';



$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET hasonlo_termekek='' WHERE hasonlo_termekek=0
  ");


echo 'Termék adatok átmásolva hasonló termékek <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET elerhetoseg = IF(keszlet>0, '4 munkanap', '')
  ");


echo 'Elérhetőség 4 munkanap <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET szallitasi_dij = '635 HUF' WHERE ar<5000
  ");

echo 'szállítás 15 ezer alatt <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET szallitasi_dij = 'Ingyenes' where ar >15000
  ");


echo 'Ingyenes szállítás 15 ezer felett <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET levelkent = IF (ar<5000,'635 Ft', 'Ingyenes')
WHERE haffner_id!='' OR gigatel_id!=''
  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET foxpost = '490 Ft'
WHERE ar <5000
  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET foxpost = '290 Ft' WHERE ar BETWEEN 5000 AND 10000
  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET foxpost = 'Ingyenes' WHERE ar>10000
  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET futarral = '1290 Ft'
WHERE ar <5000
  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET futarral = '890 Ft' WHERE ar BETWEEN 5000 AND 15000
  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET futarral = 'Ingyenes' WHERE ar>15000
  ");







$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET leiras_rovid = CONCAT('<strong>Rendkívül kedvező szállítás:</strong><br/>',
  'Foxpost-al: ','<strong>', foxpost, '</strong><br/>',
  'Levélként küldve: ','<strong>',  levelkent, '</strong><br/>',
  'Futárral szállítva: ','<strong>', futarral, '</strong>')
WHERE haffner_id!='' OR gigatel_id!=''
  ");

$result = ata_mysql_query("update wormsignh_wormsign_hu.tps_webshop
set CrossSale1=1,CrossSale2=1,CrossSale3=1,UpSale1=1,UpSale2=1
  ");






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
  SELECT youtube,sku,description FROM wormsignh_haffner.products WHERE youtube!=''
  ");


while ($row = mysql_fetch_assoc($result)) {
    if ($row['youtube']) {
        $youtube = '</br><iframe width="560" height="315" src="' . htmlentities($row['youtube']) . '"frameborder="0" allowfullscreen></iframe>';
    } else {
        $youtube = '';
    }
    $leiras = addslashes($row['description'] . $youtube);
    $sql = 'UPDATE wormsignh_wormsign_hu.tps_webshop pp'
            . ' SET pp.leiras_hosszu="' . $leiras . '"'
            . ' WHERE pp.cameron_sku="' . addslashes($row['sku']) . '"';

    ata_mysql_query($sql);
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
  SET pp.szla_id=w.szla_id,pp.name=w.megnevezes, pp.category=w.haffner_category, pp.haszonkulcs=w.haszonkulcs
  ");
echo 'SZLA-ID másolása xref-be <br/>';

$result = ata_mysql_query("UPDATE wormsignh_haffner.haffner_xref pp
  INNER JOIN wormsignh_haffner.products w ON (w.sku = pp.sku)
  SET pp.cikkszam=w.id
  ");



$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
  INNER JOIN wormsignh_wormsign_hu.product_urls w ON (w.code = pp.id)
  SET pp.product_url=w.product_url
  ");
echo 'urlek másolása tps_webshopba <br/>';


$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET export_tiltas='arukereso.hu' WHERE haszonkulcs<40 AND haffner_id!=''
  ");


echo 'Arukereso export tiltva <br/>';

//kiegészítő termékeket ajánlot products táblába, 
//az xref tábla alapján, hozzárendeljük véletlenszerűen az 5 db kiegészítő terméket

$result = ata_mysql_query("
  update wormsignh_haffner.products
set noi=1
 WHERE uj_nev LIKE '%rózsaszín%' OR uj_nev LIKE '%lila%' OR uj_nev LIKE '%pink%'
 OR uj_nev LIKE '%arany%' OR uj_nev LIKE '%piros%' OR uj_nev LIKE '%narancs%'
  ");


//xref_new frissítés product_xref-ben
//$result = ata_mysql_query("UPDATE wormsignh_haffner.product_xref pp
//  INNER JOIN wormsignh_haffner.gigatel_xref w ON (w.id = pp.id)
//  SET pp.xref_new=w.xref_new
//  ");
//
//$result = ata_mysql_query("UPDATE wormsignh_haffner.product_xref pp
//  INNER JOIN wormsignh_haffner.haffner_xref w ON (w.id = pp.id)
//  SET pp.xref_new=w.xref_new
//  ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
INNER JOIN wormsignh_haffner.gigatel w ON(pp.cameron_sku = w.sku)
SET pp.ar = w.netto_kisker_ar * 1.27, pp.haffner_category = w.new_main_category,
pp.marka=w.gyarto,pp.alt_cat=w.alt_cat,pp.megnevezes=w.ts_name,pp.kiegeszito_termekek=w.kiegeszitok
  ");



$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop
  SET export=1,
      erdeklodjon=IF(keszlet_new=0,'1','0')
      WHERE ak_id!=''
  ");


echo 'Arukereso export tiltva <br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop 
    SET keszlet_new=if(keszlet_ak='true','1',0),
    keszlet=if(keszlet_ak='true','1',0) where ak_id!=''
       ");

$result = ata_mysql_query("DELETE FROM wormsignh_haffner.products 
    WHERE short_description=''
       ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
    INNER JOIN wormsignh_haffner.akcios_arak w ON(pp.szla_id=w.sku)
    SET w.price_gross = pp.ar * w.discount
       ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
    INNER JOIN wormsignh_haffner.akcios_arak w ON(pp.szla_id=w.sku)
    SET w.price_net = w.price_gross / 1.27
       ");

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
    INNER JOIN wormsignh_haffner.akcios_arak w ON(pp.szla_id=w.sku)
    SET pp.akcios_netto_ar = w.price_net, 
    pp.akcios_brutto_ar=w.price_gross,
    pp.akcio_kezdete = w.kezdet,
    pp.akcio_lejarat = ''
       ");



echo 'Átvesszük az egyedi akciós árakat akios_arak táblából';

//  update wormsignh_wormtest.tps_webshop_feltolt
//set haszonkulcs=100-((beszerar*1.27)/ar)*100
//
//akkutkeresek kompatibilitás generálás
//
//  $result = ata_mysql_query("SELECT w.cameron_sku, w.compatible_devices_new, w.description FROM wormsignh_wormtest.tps_webshop_feltolt w");
//  while ($row = mysql_fetch_assoc($result)) {
//  $cd = $row['compatible_devices_new'];
//  $tmp = explode(',', $cd);
//  $as = array();
//  foreach ($tmp as $name) {
//  if ($name) {
//  $url = 'shop_search.php?complex=ok&search=' . urlencode($name) . '&type=2&subcat=0&in_what=00000001';
//  $as[] = '<a href="' . htmlentities($url) . '">' . htmlentities($name) . '</a>';
//  }
//  }
//
//
//
//  $desc = $row['leiras_hosszu'] . '</br>Kompatibilis típusok: ' . implode(', ', $as);
//  $desc = strtr($desc, array("\r" => ' ', "\n" => ' '));
//
//  ata_mysql_query("UPDATE wormsignh_wormtest.tps_webshop_feltolt SET leiras_hosszu=IF('" . addslashes($row['compatible_devices_new']) . "'!='','" . addslashes($desc) . "','" . addslashes($row['leiras_hosszu']) . "') "
//  . " WHERE cameron_sku = '" . addslashes($row['sku']) . "'");
//  }
// 
//echo 'Termék adatok átmásolva <br/>';



