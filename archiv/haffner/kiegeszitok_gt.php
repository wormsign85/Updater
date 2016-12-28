<?php
set_time_limit(6000);

header('Content-Type: text/html; charset=utf-8');
require_once 'lib/init.php';
require 'gt_xref_update.php';
ata_mysql_query("SET NAMES 'utf8'");

//$categoryMap = array(
//    'Képernyővédő fóliák és üvegek|Samsung' => array(    //a következő kategória esetén
//        'Akkumulátorok|Mobiltelefon akkumulátorok|Samsung', //a következő kategóriákból vegye ki a kiegészítő termékeket
//        'Autós/motoros/kerékpáros tartó|Univerzális telefontartó',
//        'Tokok és táskák|Gyári tokok, táskák|Samsung'   
//        ),
//    'Telefon hátlapok|S-Line|Samsung' => array(
//        'Telefon hátlapok|Case-Mate',
//        'Telefon hátlapok|Samsung',
//        'Képernyővédő fóliák és üvegek|Samsung'
//    )
//);

//kivételek
//$categoryMapExclude = array(
//    'Képernyővédő fóliák és üvegek|Samsung' => array(    //a következő kategória esetén
//        'Akkumulátorok|Mobiltelefon akkumulátorok|Samsung', //a következő kategóriákból vegye ki a kiegészítő termékeket
//        'Autós/motoros/kerékpáros tartó|Univerzális telefontartó',
//        'Tokok és táskák|Gyári tokok, táskák|Samsung'   
//        ),
//    'Telefon hátlapok|S-Line|Samsung' => array(
//        'Telefon hátlapok|Case-Mate',
//        'Telefon hátlapok|Samsung',
//        'Képernyővédő fóliák és üvegek|Samsung'
//    )
//);
//SELECT x2.szla_id FROM wormsignh_haffner.haffner_xref x1 INNER JOIN wormsignh_haffner.haffner_xref x2 
//        ON (x1.xref=x2.xref AND x1.szla_id != x2.szla_id) 
//        WHERE x1.sku="PT-1586" AND x2.stock > 2 AND x2.category IN ("Telefon hátlapok|S-Line|Samsung")


$result = ata_mysql_query("SELECT cikkszam,keszletinfo FROM wormsignh_haffner.gigatel WHERE keszletinfo='készleten'");
while ($row = mysql_fetch_assoc($result)) {
//   // echo $row['categories'];
//   // var_dump($categoryMap);
//    if (isset($categoryMap[$row['categories']])) {
//        $tmp = array();
//        foreach ($categoryMap[$row['categories']] as $cat) {
//            $tmp[] = '"' . addslashes($cat) . '"';
//        }
//        $categorySqlPart = ' AND x2.category IN (' . implode(',', $tmp) . ')';
//    } else {
//        $categorySqlPart = '';
//    }
//    if (isset($categoryMapExclude[$row['categories']])) {
//        $tmp = array();
//        foreach ($categoryMapExclude[$row['categories']] as $cat) {
//            $tmp[] = '"' . addslashes($cat) . '"';
//        }
//        $categorySqlPartExclude = ' AND x2.category NOT LIKE (' . implode(',', $tmp) . ')';
//    } else {
//        $categorySqlPartExclude = '';
//    }
//    $categorySqlPart_no = ' AND x2.category IN (' . implode(',', $tmp) . ')';
//    } else {
//        $categorySqlPart_no = '';
//    }

    $xrefSql = 'SELECT x2.szla_id FROM wormsignh_haffner.product_xref x1'
        . ' INNER JOIN wormsignh_haffner.product_xref x2 ON '
        . '(x1.xref_new=x2.xref_new AND x1.szla_id != x2.szla_id)'
        . ' WHERE x1.cikkszam="' . addslashes($row['cikkszam']) . '"';
    //(x2.stock>0 OR x2.gt_stock="Készleten")AND
    echo $xrefSql . '<br>';
    $result1 = ata_mysql_query($xrefSql);
    
    $newParts = array();
    while ($row1 = mysql_fetch_assoc($result1)) {
        if (!empty($row1['szla_id'])) {
            $newParts[] = $row1['szla_id'];
        }
    }
    shuffle($newParts);
    array_splice($newParts, 6);
    $kiegeszitok = implode('|', $newParts);
    ata_mysql_query('UPDATE wormsignh_haffner.gigatel SET kiegeszitok="' 
            . addslashes($kiegeszitok) . '" WHERE cikkszam="' . $row['cikkszam'] . '"');
}



echo 'Kiegészítők legenerálva<br/>';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
  INNER JOIN wormsignh_haffner.gigatel w ON (w.cikkszam = pp.gigatel_id)
  SET pp.kiegeszito_termekek=w.kiegeszitok
");

echo 'Kiegésztők másolása élesbe<br>';


//kisker/nagyker * 100 - 100 a haszon százalék számitás, ennyi van a kisker áron visszafelé számolva
//120/100 *100-100=20 % haszon a nagyker árra!

//visszafelé: 100-(100/120) *100= 16,66% van a kisker áron visszafelé számolva!
