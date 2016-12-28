<?php
$link = mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully'.'</br>';

//fizetési mód tiltás 0-s készlet esetén//

$shop_dbs = array(
'wormsignh_wormsign_hu',
);

foreach ($shop_dbs as $db_name)
{
    mysql_query('
    UPDATE ' . $db_name . '.tps_webshop pp
    SET pp.szallitasi_mod_tiltas = 168612
    WHERE pp.ar<10000
    ', $link);

    echo 'Szállítás mód tiltva 10000-nél olcsóbb termékeknél: ' . $db_name . '<br/>';
	
}

$shop_dbs = array(
'wormsignh_wormsign_hu',
);

foreach ($shop_dbs as $db_name)
{
    mysql_query("
    UPDATE wormsignh_wormsign_hu.tps_webshop
    SET szallitasi_dij='Ingyenes'
    WHERE szallitasi_mod_tiltas != 168612
    ", $link);

    echo 'Szállítás inygenes 10 000nél drágább terméknél ' . $db_name . '<br/>';
	
}
	

exit;
mysql_close($link);