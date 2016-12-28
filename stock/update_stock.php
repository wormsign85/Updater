<?php

error_reporting(E_ALL);
//echo getcwd();
//require_once __DIR__ .  '../../lib/init.php';

//$link = mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
//if (!$link) {
//    die('Could not connect: ' . mysql_error());
//}
//mysql_select_db("wormsignh_wormsign_hu");
//
//function ata_mysql_query($sql, $link = null) {
//    if ($link) {
//        $result = mysql_query($sql, $link);
//    } else {
//        $result = mysql_query($sql);
//    }
//    if (!$result) {
//        die('SQL hiba: ' . mysql_error() . ' SQL: ' . $sql);
//    }
//    return $result;
//}

try {
    $conn = new PDO($config_db_stock['connection'], $config_db_stock['username'], $config_db_stock['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

//update db-ben free_Stock sz�m�t�sa k�szletek �s foglal�sok alapj�n

$sql1 = " UPDATE wormsignh_update.full_stock 
         SET free_stock=(quantity-nonstrictallocate)+kp_quantity ";

try {
    $sth = $conn->prepare($sql1);
    $statement = $sth->execute();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}

//update db-ben free_Stock sz�m�t�sa k�szletek �s foglal�sok alapj�n


$sql2 = " insert ignore into wormsignh_update.full_stock
(productcode, cameron_sku, szla_id, name)
SELECT id, cameron_sku, id, megnevezes FROM wormsignh_wormsign_hu.tps_webshop ";

try {
    $sth = $conn->prepare($sql2);
    $statement = $sth->execute();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}

//átmásoljuk a 3 használt táblából egy táblába a termékeket, hogy utána ráfrissíthessük a készletüket!
//ugyan ide frissítjük be a kapacitás készletét is
//majd innen figyelve a változásokat, feltöltjük unasba egyben
// ez talán jobb az eddigieknél, mert igy nem a cikkszámoka párosítjuk 
// a webre feltöltött és symbolból jövő készlethez, hanem a tényleges termékekhez frissítjük a készletet
//ráadásul egyből két helyről



$sql3 = " insert ignore into wormsignh_update.full_stock
(productcode, cameron_sku, szla_id, name, xrefid)
SELECT szla_id, cameron_sku, szla_id, megnevezes, id FROM wormsignh_wormsign_hu.tps_webshop_new ";

try {
    $sth = $conn->prepare($sql3);
    $statement = $sth->execute();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}


$sql4 = " insert ignore into wormsignh_update.full_stock
(productcode, cameron_sku, szla_id, name, xrefid)
SELECT szla_id, cameron_sku, szla_id, megnevezes, id FROM wormsignh_wormsign_hu.tps_webshop_img";

try {
    $sth = $conn->prepare($sql4);
    $statement = $sth->execute();
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}




echo 'Siker';

require_once '../stock/unas/upload_stock.php';