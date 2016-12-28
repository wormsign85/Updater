<?php

set_time_limit(600);

header('Content-Type: text/html; charset=utf-8');
require_once 'init.php';
require_once 'actions.php';

mysql_query("set names 'utf8'", $connection);


$result = mysql_query("SET collation_connection = 'utf8_general_ci'");
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

echo 'utf8_general <br/>';


$result = mysql_query("ALTER TABLE wormsignh_wormsign_hu.symbol CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
if (!$result) {
    die('Invalid query: ' . mysql_error());
}
echo ' utf8 <br/>';




$result = mysql_query("
  INSERT IGNORE INTO wormsignh_wormsign_hu.symbol
  (haffner_id, szla_id)
  SELECT haffner_id, szla_id FROM wormsignh_wormsign_hu.tps_webshop
  WHERE haffner_id!=0
  ");
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

echo 'Új termékek átmásolva tps_webshopba' . '<br/>';


$result = mysql_query("UPDATE wormsignh_wormsign_hu.symbol pp
  INNER JOIN wormsignh_wormsign_hu.tps_webshop w ON (w.haffner_id = pp.haffner_id)
  SET pp.name=w.megnevezes,pp.category=w.haffner_category,pp.shipper='5798SZ',pp.listaar=w.ar/1.27,pp.listaar_rp=w.ar,pp.beszerar=w.beszerar,pp.haf_sku=w.cameron_sku
WHERE w.haffner_id!=0
  ");
if (!$result) {
    die('Invalid query: ' . mysql_error());
}
?>
