<?php
$link = mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
INNER JOIN wormsignh_atvetel.prices w ON(pp.szla_id=w.productcode)
 SET pp.ar=w.value*1.27 WHERE w.pricecategoryName='Listaár'",
 $link);