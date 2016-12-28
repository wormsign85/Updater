<?php
require_once 'szallitas_tiltas.php';


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
    SET pp.fizetesi_mod_tiltas = 141871
    WHERE keszlet < 2 AND haffner_id=0
    ', $link);

    echo 'Fizetési mód tiltva 2-nél kisebb készletnél: ' . $db_name . '<br/>';
	
}

$shop_dbs = array(
'wormsignh_wormsign_hu',
);

foreach ($shop_dbs as $db_name)
{
    mysql_query('
    UPDATE ' . $db_name . '.tps_webshop pp
    SET pp.fizetesi_mod_tiltas =""
    WHERE keszlet > 2
    ', $link);

    echo 'Fizetési mód engedélyezés 2-nél nagyobb készletnél: ' . $db_name . '<br/>';
	
}
	

exit;
mysql_close($link);