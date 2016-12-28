<?php



error_reporting(E_ALL);

$link = mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
//if (!$link) {
//    die('Could not connect: ' . mysql_error());
//}
//mysql_select_db("wormsignh_wormsign_hu");

function ata_mysql_query($sql, $link = null) {
    if ($link) {
        $result = mysql_query($sql, $link);
    } else {
        $result = mysql_query($sql);
    }
    if (!$result) {
        die('SQL hiba: ' . mysql_error() . ' SQL: ' . $sql);
    }
    return $result;
}


//update db-ben free_Stock sz�m�t�sa k�szletek �s foglal�sok alapj�n
ata_mysql_query("UPDATE wormsignh_update.stock "
        . " SET free_stock=quantity-nonstrictallocate");

echo 'Siker';

//tps_webshop friss�t�s free stockb�l
ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
        INNER JOIN wormsignh_update.stock w ON(w.productcode=pp.szla_id)
        SET pp.keszlet=w.free_stock, pp.keszletdate = CURRENT_TIMESTAMP 
        WHERE w.warehousename='Központi raktár' ");
echo 'Siker';

ata_mysql_query("UPDATE wormsignh_update.stock pp
        INNER JOIN wormsignh_wormsign_hu.szla_id w ON(w.szla_id=pp.productcode)
        SET pp.cameron_sku=w.cameron_sku ");

ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop_new pp
        INNER JOIN wormsignh_update.stock w ON(w.cameron_sku=pp.cameron_sku)
        SET pp.keszlet_ws=w.free_stock 
        WHERE w.warehousename='Központi raktár' ");

ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop_img pp
        INNER JOIN wormsignh_update.stock w ON(w.cameron_sku=pp.cameron_sku)
        SET pp.keszlet_ws=w.free_stock 
        WHERE w.warehousename='Központi raktár' ");



echo 'Siker';
require_once '../../wormsign_hu/update/stock_upload_akkucentral.php';
require_once '../../wormsign_hu/update/stock_upload_akkucentral_1.php';
require_once '../../wormsign_hu/update/stock_upload_akkucentral_2.php';
