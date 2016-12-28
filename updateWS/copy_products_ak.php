<?php

set_time_limit(1200);

header('Content-Type: text/html; charset=utf-8');
require_once 'lib/init.php';


ata_mysql_query("set names 'utf8'", $connection);



//akkutkeresek kompatibilitás generálás
  
$result = ata_mysql_query("SELECT w.cameron_sku, w.compatible_devices_new, w.leiras_hosszu FROM wormsignh_wormtest.tps_webshop_feltolt w");
while ($row = mysql_fetch_assoc($result)) {
    $cd = $row['compatible_devices_new'];
    $tmp = explode(',', $cd);
    $as = array();
    foreach ($tmp as $name) {
        if ($name) {
            $url = 'shop_search.php?complex=ok&search=' . urlencode($name) . '&type=2&subcat=0&in_what=10000000';
            $as[] = '<a href="' . htmlentities($url) . '">' . htmlentities($name) . '</a>';
        }
    }


    
    $desc = $row['leiras_hosszu'] . '</br>Kompatibilis típusok: ' . implode(', ', $as);
    $desc = strtr($desc, array("\r" => ' ', "\n" => ' '));

    ata_mysql_query("UPDATE wormsignh_wormtest.tps_webshop_feltolt SET compatible_desc=IF('" . addslashes($row['compatible_devices_new']) . "'!='','" . addslashes($desc) . "','" . addslashes($row['leiras_hosszu']) . "') "
            . " WHERE cameron_sku = '" . addslashes($row['cameron_sku']) . "'");
}

echo 'Termék adatok átmásolva <br/>';