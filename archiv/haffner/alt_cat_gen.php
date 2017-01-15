<?php

set_time_limit(6000);

require_once 'lib/init.php';
//require_once 'lib/szetbont_marka_tipus.php';

require_once 'tok_alt_cat.php';
require_once 'folia_alt_cat.php';
require_once 'hatlap_alt_cat.php';

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
  INNER JOIN wormsignh_haffner.products w ON (w.id = pp.haffner_id)
  SET pp.alt_cat=w.alt_cat
");

echo 'ALkategoriák másolása élesbe<br>';