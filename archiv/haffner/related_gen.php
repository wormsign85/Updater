<?php
set_time_limit (6000);

header('Content-Type: text/html; charset=utf-8');
require_once 'lib/init.php';

    //realted products generálás, cikkszám konvertálása
$map = array();
$result = mysql_query('SELECT szla_id, cameron_sku FROM wormsignh_wormsign_hu.tps_webshop WHERE cameron_sku!=""');
while ($row = mysql_fetch_assoc($result))
{
    $map[$row['cameron_sku']] = $row['szla_id']; 
}

$result = mysql_query('SELECT id, related_products FROM wormsignh_haffner.products');
while ($row = mysql_fetch_assoc($result))
{
    $rp = $row['related_products'];
    $parts = explode('|', $rp);
    $newParts = array();
    foreach ($parts as $part) {
        if (!empty($map[$part])) {
            $newParts[] = $map[$part];
        }
    }
    $new_related = implode('|', $newParts);
    mysql_query('UPDATE wormsignh_haffner.products SET new_related="' . addslashes($new_related) . '" WHERE id=' . $row['id']);
}



$result = mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
  INNER JOIN wormsignh_haffner.products w ON (w.id = pp.haffner_id)
  SET pp.hasonlo_termekek=w.new_related
  ");
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

