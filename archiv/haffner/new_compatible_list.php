<?php
set_time_limit(6000);

header('Content-Type: text/html; charset=utf-8');
require_once 'lib/init.php';

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
$categoryMapExclude = array(
    'Képernyővédő fóliák és üvegek|Samsung' => array(    //a következő kategória esetén
        'Akkumulátorok|Mobiltelefon akkumulátorok|Samsung', //a következő kategóriákból vegye ki a kiegészítő termékeket
        'Autós/motoros/kerékpáros tartó|Univerzális telefontartó',
        'Tokok és táskák|Gyári tokok, táskák|Samsung'   
        ),
    'Telefon hátlapok|S-Line|Samsung' => array(
        'Telefon hátlapok|Case-Mate',
        'Telefon hátlapok|Samsung',
        'Képernyővédő fóliák és üvegek|Samsung'
    )
);
//SELECT x2.szla_id FROM wormsignh_haffner.haffner_xref x1 INNER JOIN wormsignh_haffner.haffner_xref x2 
//        ON (x1.xref=x2.xref AND x1.szla_id != x2.szla_id) 
//        WHERE x1.sku="PT-1586" AND x2.stock > 2 AND x2.category IN ("Telefon hátlapok|S-Line|Samsung")


$result = ata_mysql_query('SELECT sku, categories FROM wormsignh_haffner.products');
while ($row = mysql_fetch_assoc($result)) {
   // echo $row['categories'];
   // var_dump($categoryMap);
    if (isset($categoryMap[$row['categories']])) {
        $tmp = array();
        foreach ($categoryMap[$row['categories']] as $cat) {
            $tmp[] = '"' . addslashes($cat) . '"';
        }
        $categorySqlPart = ' AND x2.category IN (' . implode(',', $tmp) . ')';
    } else {
        $categorySqlPart = '';
    }
    if (isset($categoryMapExclude[$row['categories']])) {
        $tmp = array();
        foreach ($categoryMapExclude[$row['categories']] as $cat) {
            $tmp[] = '"' . addslashes($cat) . '"';
        }
        $categorySqlPartExclude = ' AND x2.category NOT IN (' . implode(',', $tmp) . ')';
    } else {
        $categorySqlPartExclude = '';
    }
//    $categorySqlPart_no = ' AND x2.category IN (' . implode(',', $tmp) . ')';
//    } else {
//        $categorySqlPart_no = '';
//    }

    $xrefSql = 'SELECT x2.xref_new FROM wormsignh_haffner.xref x1'
        . ' INNER JOIN wormsignh_haffner.xref x2 ON (x1.xref_new=x2.xref_new)'
        . ' WHERE x1.sku="' . addslashes($row['sku']) . '"';
    echo $xrefSql . '<br>';
    $result1 = ata_mysql_query($xrefSql);
    
    $newParts = array();
    while ($row1 = mysql_fetch_assoc($result1)) {
        if (!empty($row1['szla_id'])) {
            $newParts[] = $row1['szla_id'];
        }
    }
    shuffle($newParts);
    array_splice($newParts, 1000);
    $kiegeszitok = implode('*', $newParts);
    ata_mysql_query('UPDATE wormsignh_haffner.products SET compatible_devices_new="' . addslashes($kiegeszitok) . '" WHERE sku="' . $row['sku'] . '"');
}



echo 'Új kompatibilis lista legenerálva<br/>';





//kisker/nagyker * 100 - 100 a haszon százalék számitás, ennyi van a kisker áron visszafelé számolva
//120/100 *100-100=20 % haszon a nagyker árra!

//visszafelé: 100-(100/120) *100= 16,66% van a kisker áron visszafelé számolva!
