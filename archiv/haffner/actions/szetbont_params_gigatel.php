<?php

set_time_limit(6000);
require_once 'init.php';

//require_once 'lib/actions.php';
require_once 'szetbont_nev_params_gigatel.php';

ata_mysql_query("set names 'utf8'", $connection);

//kiszedjük az uj_névből a márkákat cat_brand-ba
$map = array();
$result = mysql_query("SELECT cikkszam, uj_nev FROM wormsignh_haffner.gigatel");
while ($row = mysql_fetch_assoc($result)) {
    $oszlopok = szetbontTulajdonsagok($row['uj_nev']);
    if ($oszlopok) {
        $sqlOszlopok = array();
        foreach ($oszlopok as $oszlop => $ertek) {
            $sqlOszlopok[] = $oszlop . '=' . '"' . addslashes($ertek) . '"';
        }

        $setek = implode(',', $sqlOszlopok);
        $letrehoz = mysql_query('UPDATE wormsignh_haffner.gigatel_params SET ' . $setek . ' WHERE cikkszam="' . addslashes($row['cikkszam']) . '"');
        if (!$letrehoz) {
            die('Could not connect: ' . mysql_error());
        }
    }
}
echo 'Paraméterek kiírva gigatel db';


//kiszedjük az új nevekből a típusokat, amiket felvittünk szetbont nev params gigába, beirom paramsba
$map = array();
$result = mysql_query("SELECT cikkszam, uj_nev FROM wormsignh_haffner.gigatel_params");
while ($row = mysql_fetch_assoc($result)) {
    $oszlopok = szetbontTulajdonsagok($row['uj_nev']);
    if ($oszlopok) {
        $sqlOszlopok = array();
        foreach ($oszlopok as $oszlop => $ertek) {
            $sqlOszlopok[] = $oszlop . '=' . '"' . addslashes($ertek) . '"';
        }

        $setek = implode(',', $sqlOszlopok);
        $letrehoz = mysql_query('UPDATE wormsignh_haffner.gigatel_params SET ' . $setek . ' WHERE cikkszam="' . addslashes($row['cikkszam']) . '"');
        if (!$letrehoz) {
            die('Could not connect: ' . mysql_error());
        }
    }
}
echo 'Paraméterek kiírva gigatel_params-ba db';

