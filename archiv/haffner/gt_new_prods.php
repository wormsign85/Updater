
<?php

SET_time_limit(1200);

$feedid = isset($_GET['feedid']) ? $_GET['feedid'] : null;
//header('Content-Type: text/html; charSET=utf-8');


$initfile = __DIR__ . '/lib/init.php';
if (file_exists($initfile)) {
    // lokális
    require_once $initfile;

    $user = 'root';
    $pass = '';
} else {
    // éles
    require_once __DIR__ . '/lib/init.php';

    $user = 'wormsignh_worm';
    $pass = 'IxOn1985';
}
//kiszedjük a márkaneveket a termék nevekből, vagy a típusokból, hogy legyen 3. szintű kategóriánk

//require_once  __DIR__ . '/lib/szetbont_params_gigatel.php';

//require 'gigatel_update_ts.php';


ata_mysql_query("SET names 'utf8'", $connection);

//gigatel-ből átmásoljuk a már új névvel ellátott terméekeket, 
//fel paraméterezzük és majd innen visszük fel tps-webshopba az új termékeket

$result = ata_mysql_query("
  INSERT IGNORE INTO wormsignh_haffner.gigatel_params
  (cikkszam, uj_nev, sku,cikkfajta,cikkcsoport, letrehozva)
  SELECT cikkszam, uj_nev, sku, cikkfajta, cikkcsoport, NOW() FROM wormsignh_haffner.gigatel WHERE uj_nev != ''
  ");

echo 'Gigatelből új névvel ellátott termékeke átmásolva<br>';


//gigatel db-be beírjuk az új kategóriák előtagját
// Ha a névben van: tok, akkor beírjuk az első szintű kategóriát: MOBIL_Telefon Tokok
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel
SET kateg_elotag = 'MOBIL_Telefon Tokok|' WHERE uj_nev like '%tok%'
  ");

//Ha a cikkcsoportban van: tok, beírjuk az első szintű kategóriát: MOBIL_Telefon Tokok
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel
SET kateg_elotag = 'MOBIL_Telefon Tokok|' WHERE cikkcsoport like '%tok%'
  ");

//Ha a névben van: tablet tok, beírjuk az első szintű kategóriát: MOBIL_Tablet Tokok
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel
SET kateg_elotag = 'MOBIL_Tablet Tokok|' WHERE uj_nev like '%tablet tok%'
  ");

//fóliák bektagorizálása
//Ha a csikkcsoportban van: tok, beírjuk az első szintű kategóriát: MOBIL_Telefon védőfóliák
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel
SET kateg_elotag = 'MOBIL_Telefon védőfóliák|' WHERE cikkcsoport like '%fólia%'
  ");
//Ha a névben van: tok, beírjuk az első szintű kategóriát: MOBIL_Telefon védőfóliák
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel
SET kateg_elotag = 'MOBIL_Telefon védőfóliák|' WHERE uj_nev like '%fólia%'
  ");

//Ha a névben csak a fólia szerpeel, akkor beirjuk 2. szintű kategóriának, hogy Kijelzovédo fólia
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel
SET subcategory1 = 'Kijelzovédo fólia' WHERE uj_nev like '%fólia%'
  ");

//Ha a névben szerepel, hogy ütésálló, akkor beirjuk 2. szintű kategóriának, hogy Ütésálló fóliák
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel
SET subcategory1 = 'Ütésálló fóliák' WHERE uj_nev like '%ütésálló%'
  ");


//eddig ok


//ha NEM üres a  a gigatel tábla subcategory1 mező params táblában, akkor összefűzzük a kateg_elotagot, a subcategory1-et, 
//és hozzáadjuk a kiszedett márkanevet 3. szintű kategóriaként
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel_params pp
    INNER JOIN wormsignh_haffner.gigatel w ON(pp.cikkszam=w.cikkszam)
        SET pp.full_subcategory1= IF(w.subcategory1!='',
        CONCAT(w.kateg_elotag , w.subcategory1, '|' , pp.new_cat_brand),'') WHERE pp.new_cat_brand!=''
  ");

//ha NEM üres a subcategory2 mező params táblában, akkor összefűzzük a kateg_elotagot, a subcategory2-t, 
//és hozzáadjuk a kiszedett márkanevet 3. szintű kategóriaként
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel_params pp
    INNER JOIN wormsignh_haffner.gigatel w ON(pp.cikkszam=w.cikkszam)
        SET pp.full_subcategory2= IF(w.subcategory2!='',
        CONCAT(w.kateg_elotag , w.subcategory2, '|' , pp.new_cat_brand),'') WHERE pp.new_cat_brand!=''
  ");

//ha NEM üres a subcategory3 mező params táblában, akkor összefűzzük a kateg_elotagot, a subcategory3-at, 
//és hozzáadjuk a kiszedett márkanevet 3. szintű kategóriaként
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel_params pp
    INNER JOIN wormsignh_haffner.gigatel w ON(pp.cikkszam=w.cikkszam)
        SET pp.full_subcategory3= IF(w.subcategory3!='',
        CONCAT(w.kateg_elotag , w.subcategory3, '|' , pp.new_cat_brand),'') WHERE pp.new_cat_brand!=''
  ");

//legeneráljuk a kategóriákat gigatel db-ben megadott kategória nevek alapján és beírjuk params-ba

//innen nem oké

//HA nincs márkanév, amit a kategóriába tehetnénk, akkor ezeket csináljuk:

//$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel_params pp
//    INNER JOIN wormsignh_haffner.gigatel w ON(pp.cikkszam=w.cikkszam)
//        SET pp.full_subcategory1= IF(w.subcategory1='Univerzális Tokok',
//        CONCAT(w.kateg_elotag , w.subcategory1),'') WHERE pp.new_cat_brand!=''
//  ");
//
//$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel_params pp
//    INNER JOIN wormsignh_haffner.gigatel w ON(pp.cikkszam=w.cikkszam)
//        SET pp.full_subcategory2= IF(w.subcategory2='Univerzális Tokok',
//        CONCAT(w.kateg_elotag , w.subcategory2),'') WHERE pp.new_cat_brand!=''
//  ");
//
//$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel_params pp
//    INNER JOIN wormsignh_haffner.gigatel w ON(pp.cikkszam=w.cikkszam)
//        SET pp.full_subcategory3= IF(w.subcategory3='Univerzális Tokok',
//        CONCAT(w.kateg_elotag , w.subcategory3),'') WHERE pp.new_cat_brand!=''
//  ");

//gigatel paramsból átirjuk az uj neveket ts-name mezőbe gigatel db-ben
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel pp
    INNER JOIN wormsignh_haffner.gigatel_params w ON(pp.cikkszam=w.cikkszam)
        SET pp.ts_name= w.uj_nev
  ");

//gigatel db-be beírjuk a legenerált főkategóriákat paramsból
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel pp
    INNER JOIN wormsignh_haffner.gigatel_params w ON(pp.cikkszam=w.cikkszam)
        SET pp.new_main_category=IF(w.full_subcategory1!='', w.full_subcategory1,w.full_subcategory2)
  ");


$result = ata_mysql_query("
  INSERT IGNORE INTO wormsignh_wormsign_hu.tps_webshop
  (gigatel_id, megnevezes, haffner_category, cameron_sku, ar, letrehozva)
  SELECT cikkszam, ts_name, new_main_category, sku, brutto_kisker_ar, NOW() 
  FROM wormsignh_haffner.gigatel WHERE uj_nev != ''
  ");

echo 'Gigatelből átmásoljuk a már átnevezett és bekategorizált termékeket tps_webshopba.<br>';

//átmásoljuk a tulajdonságokat élesbe
$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
    INNER JOIN wormsignh_haffner.gigatel w ON(pp.gigatel_id=w.cikkszam)
        SET pp.image_url=w.termekkep, pp.haffner_category=w.new_main_category, pp.active= IF(w.new_product='1','2','1')
  ");

//Beállítjuk az átmásolt új termékeket ÚJ ként
$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel pp
    INNER JOIN wormsignh_haffner.gigatel_params w ON(pp.cikkszam=w.cikkszam)
  SET pp.new_product=IF(w.letrehozva>'" . date('Y-m-d H:i:s', time() - 30 * 24 * 3600) . "','1','0')
  ");

//if (empty($_GET['test'])) {
//    ;
//} else {
//    $fp = require_once '/lib/szetbont_params_gigatel.php');
//}