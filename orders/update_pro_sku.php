<?php
require_once '../../lib/init.php';
/*
$link = mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

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
}*/

//itt beirjuk a cameron_skut az orders_itesmbe az unasból letöltött cikkszám egyezése alapján

ata_mysql_query("UPDATE wormsignh_mydb.orders_items oi
INNER JOIN wormsignh_update.full_stock wx ON(oi.Item_sku = wx.xrefid)
SET oi.symbol_id=wx.productcode");



//itt átirjuk a speciális termékek cikkszámát a megfelelőre
ata_mysql_query("UPDATE wormsignh_mydb.orders_items
set symbol_id='shipping-cost'
WHERE Item_Id='shipping-cost'");

ata_mysql_query("UPDATE wormsignh_mydb.orders_items
set symbol_id='discount-amount'
WHERE Item_Id='discount-amount'");

/*
ata_mysql_query("UPDATE wormsignh_mydb.orders_items oi
INNER JOIN wormsignh_wormtest.kodok ko ON(oi.Item_Sku = ko.rp_code)
SET oi.symbol_id=ko.sy_code",
 $link);

ata_mysql_query("UPDATE wormsignh_mydb.orders_items oi
INNER JOIN wormsignh_wormsign_hu.felujitas_kodok ko ON(oi.Item_Sku = ko.id)
SET oi.symbol_id=ko.sy_code",
 $link);
*/









