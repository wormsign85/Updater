<?php

SET_time_limit(1200);

//header('Content-Type: text/html; charSET=utf-8');
require_once 'lib/init.php';


ata_mysql_query("SET names 'utf8'", $connection);


//$result = ata_mysql_query("
//  INSERT IGNORE INTO wormsignh_haffner.gt_tokshop
//  (item_no, category, subcategory,name,price,alt_cat)
//  SELECT item_no, category, subcategory,name,price,alt_cat FROM wormsignh_haffner.gt_full
//  ");


//echo 'Új termékek átmásolva gt_tokshopba' . '<br/>';


//
//$result = ata_mysql_query("
//  INSERT IGNORE INTO wormsignh_haffner.gt_tokshop
//  (item_no, category, subcategory,name,price)
//  SELECT item_no, category, subcategory,name,price FROM wormsignh_haffner.gt_folia
//  ");
//
//$result = ata_mysql_query("
//  INSERT IGNORE INTO wormsignh_haffner.gt_tokshop
//  (item_no, category, subcategory,name,price)
//  SELECT item_no, category, subcategory,name,price FROM wormsignh_haffner.gt_tartok
//  ");
//
//$result = ata_mysql_query("
//  INSERT IGNORE INTO wormsignh_haffner.gt_tokshop
//  (item_no, category, subcategory,name,price)
//  SELECT item_no, category, subcategory,name,price FROM wormsignh_haffner.gt_akkuk
//  ");
//
$result = ata_mysql_query("
update wormsignh_haffner.gigatel pp
inner join wormsignh_haffner.gt_tokshop w on(pp.cikkszam=w.gt_sku)
set pp.ts_name=w.name,pp.new_main_category=w.full_main_category
  ");
//
//$result = ata_mysql_query("
//update wormsignh_haffner.gt_tokshop pp
//inner join wormsignh_haffner.gt_akkuk w on(pp.gt_sku=w.gt_sku)
//set pp.category=w.category
//  ");
//
//
//echo 'Új termékek átmásolva gt_tokshopba' . '<br/>';
//$result = ata_mysql_query("
//  INSERT IGNORE INTO wormsignh_haffner.gt_tokshop
//  (item_no, category, subcategory,name,price,alt_cat)
//  SELECT item_no, category, subcategory,name,price,alt_cat FROM wormsignh_haffner.gt_markas
//  ");
//
//
//echo 'Új termékek átmásolva gt_tokshopba' . '<br/>';
//
//$result = ata_mysql_query("
//  INSERT IGNORE INTO wormsignh_haffner.gt_tokshop
//  (item_no, category, subcategory,name,price,alt_cat)
//  SELECT item_no, category, subcategory,name,price,alt_cat FROM wormsignh_haffner.gt_lenyithato
//  ");
//
//
//echo 'Új termékek átmásolva gt_tokshopba' . '<br/>';
//
//foreach ($tables as $table) {
//    $result = ata_mysql_query("UPDATE gt_tokshop pp
//    inner join $table w ON(pp.item_no=w.item_no)
//SET pp.subcategory=w.subcategory, pp.name=w.name
//");
//    echo 'Adatok frissítése itt:' . $table . '<br/>';
//}
//tokshop db frissítése gt_full db-ből
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop pp
    inner join wormsignh_haffner.gt_full w ON(pp.item_no=w.item_no)
SET pp.subcategory=w.subcategory, pp.name=w.name
  ");

//tokshop db ben telefon tokok kategoria generálása azokhoz, aminek az eredeti kategóriája telefon tokok

$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET category='MOBIL_Telefon Tokok' WHERE category ='Telefon Tokok'
  ");

//tokshopban telefon hátlapok kategorizálása aminek bumper volt a kategja
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET category='MOBIL_Telefon Hátlapok' WHERE subcategory ='Bumper'
  ");

//tokshopban alkategoria frissítése bumper hátlapokra
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET subcategory='Bumper Hátlapok' WHERE subcategory ='Bumper'
  ");

//$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
//SET subcategory='Mintás Hátlapok' WHERE subcategory ='Köves és mintás tokok'
//  ");
//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET category='MOBIL_Telefon Hátlapok' WHERE subcategory ='Műanyag hátlap tokok'
  ");

//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET subcategory='Hátlapok' WHERE subcategory ='Műanyag hátlap tokok'
  ");

//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET subcategory='Oldalra Nyitható Tokok' WHERE subcategory ='Oldalra nyíló tokok'
  ");

//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET subcategory='Oldalra Nyitható Tokok' WHERE name LIKE '%oldalra nyíló%'
  ");

//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET category='MOBIL_Telefon Tokok' WHERE subcategory ='Oldalra Nyitható Tokok'
  ");

//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET category='MOBIL_Telefon Hátlapok' WHERE subcategory ='Szilikon tok'
  ");

//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET subcategory='Szilikon Hátlapok' WHERE subcategory ='Szilikon tok'
  ");

//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET category='MOBIL_Telefon Hátlapok' WHERE subcategory ='S-Line series'
  ");

//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET subcategory='S-Line Hátlapok' WHERE subcategory ='S-Line series'
  ");

//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET new_alt_subcategory='Alumínium Hátlapok' WHERE name LIKE '%alumínium%' AND subcategory LIKE '%Bumper%'
  ");

//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET category='MOBIL_Telefon Hátlapok' WHERE subcategory ='Mintás Hátlapok'
  ");

//tokshopban főkategoria frissítése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET new_alt_subcategory='Szilikon Hátlapok' WHERE name LIKE '%szilikon tok%' AND subcategory LIKE '%Mintás%'
  ");

//tokshopban 3 kategoria összefűzése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET full_main_category=CONCAT(category, '|', subcategory, '|', cat_brand)
  ");


//tokshopban 3 kategoria összefűzése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET new_alt_category=CONCAT(category, '|', new_alt_subcategory, '|', cat_brand) WHERE new_alt_subcategory !=''
  ");

//tokshopban 3 kategoria összefűzése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET alt1=CONCAT(category, '|', subcategory1, '|', cat_brand) WHERE subcategory1 !=''
  ");

//tokshopban 3 kategoria összefűzése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET alt2=CONCAT(category, '|', subcategory2, '|', cat_brand) WHERE subcategory2 !=''
  ");

//tokshopban 3 kategoria összefűzése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET alt3=CONCAT(category, '|', subcategory3, '|', cat_brand) WHERE subcategory3 !=''
  ");

//$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel pp
//    INNER JOIN wormsignh_haffner.gt_tokshop w ON(pp.cikkszam = w.gt_sku)
//SET pp.alt_cat=IF(w.alt1!='',CONCAT(pp.alt_cat, '@' , w.alt1),'')
//  ");
//
//$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel pp
//    INNER JOIN wormsignh_haffner.gt_tokshop w ON(pp.cikkszam = w.gt_sku)
//SET pp.alt_cat=IF(w.alt2!='',CONCAT(pp.alt_cat, '@' , w.alt2),'')
//  ");
//
//$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel pp
//    INNER JOIN wormsignh_haffner.gt_tokshop w ON(pp.cikkszam = w.gt_sku)
//SET pp.alt_cat=IF(w.alt3!='',CONCAT(pp.alt_cat, '@' , w.alt3),'')
//  ");
//tokshopban noi tokok jelölése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET noi='1' WHERE name LIKE '%pillangó%' OR name LIKE '%virág%' 
  ");

//tokshopban noi tokok jelölése
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET noi='1' WHERE name LIKE '%rózsaszín%' OR name LIKE '%pink%' OR name LIKE '%piros%'
OR name LIKE '%lila%' OR name LIKE '%szív%' 
  ");


$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET subcategory1='Hívásbetekintős Tokok'
WHERE new_name LIKE '%hívás mutatóval%' AND subcategory LIKE '%Tokok%'
  ");


$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET subcategory2='Mintás tokok'
WHERE (new_name LIKE '%gravírozott%' OR new_name LIKE '%szegecses%'
OR new_name LIKE '%köves%' OR new_name LIKE '%minta%' OR new_name LIKE '%mintás%')
AND subcategory LIKE '%Tokok%'
  ");


//tokshopban márkás tokok kategorizálása
$result = ata_mysql_query("UPDATE wormsignh_haffner.gt_tokshop
SET category='MOBIL_Márkás Tokok' WHERE subcategory IN 
('Mercury','ProFlip',
'Rock',
'Forcell Slim Flip Elite',
'CG Mobile',
'LG gyári tokok',
'Guess',
'Samsung gyári tokok',
'Kalaideng',
'HTC gyári tokok',
'Xperia gyári tokok',
'Bugatti',
'Nillkin',
'Nokia gyári tokok',
'Krusell',
'Griffin',
'Baseus',
'Case-Mate',
'Usams tokok',
'Fekvő övre',
'Love Mei',
'Alcatel gyári tokok',
'iPhone gyári tokok',
'Muvit',
'Huawei gyári tokok',
'Sport karpánt',
'Mofi',
'Otterbox',
'Adidas',
'Asus gyári tokok')
  ");






//$result = ata_mysql_query("
//  UPDATE wormsignh_haffner.gt_tokshop
//  SET new_name = REPLACE(name, ('Oldalra nyíló tok,'), 'Oldalra Nyitható Tok,'),
//      new_name = REPLACE(name, ('oldalra nyíló tok,'), 'Oldalra Nyitható Tok,'),
//      new_name = REPLACE(name, ('stand, '), '')
//  ");
//
//Tokshop adatok átirása Gigatel db-be



$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel pp
INNER JOIN wormsignh_haffner.gt_tokshop w ON(pp.cikkszam = w.gt_sku)
SET pp.ts_name = w.name, pp.new_main_category = w.full_main_category 
WHERE w.full_main_category !=''
  ");

//gigatel db-ben alkategoriák összefűzése alt_cat mezőbe
$result = ata_mysql_query("
update wormsignh_haffner.gigatel pp
INNER join wormsignh_haffner.gt_tokshop w ON(pp.cikkszam = w.gt_sku)
set pp.alt_cat = if(pp.alt_cat like concat('%', w.alt1, '%'),
 pp.alt_cat, concat(pp.alt_cat, IF(pp.alt_cat!='', '@', ''), w.alt1))
  ");

$result = ata_mysql_query("
         update wormsignh_haffner.gigatel pp
INNER join wormsignh_haffner.gt_tokshop w ON(pp.cikkszam = w.gt_sku)
set pp.alt_cat = if(pp.alt_cat like concat('%', w.alt2, '%'),
 pp.alt_cat, concat(pp.alt_cat, IF(pp.alt_cat!='', '@', ''), w.alt2))
  ");

$result = ata_mysql_query("
         update wormsignh_haffner.gigatel pp
INNER join wormsignh_haffner.gt_tokshop w ON(pp.cikkszam = w.gt_sku)
set pp.alt_cat = if(pp.alt_cat like concat('%', w.alt3, '%'),
 pp.alt_cat, concat(pp.alt_cat, IF(pp.alt_cat!='', '@', ''), w.alt3))
  ");

//átmásoljuk tps_webshopba azokat a termékeket, aminek van tokshopos kategóriájuk (new_main_category)

$result = ata_mysql_query("
  INSERT IGNORE INTO wormsignh_wormsign_hu.tps_webshop
  (gigatel_id, cameron_sku, megnevezes, haffner_category,marka,ar,image_url, letrehozva, updated_at)
  SELECT cikkszam, sku, ts_name, new_main_category, gyarto, 
  netto_kisker_ar, termekkep, letrehozva, updated_at
  FROM wormsignh_haffner.gigatel
  WHERE new_main_category !=''
  ");




//gigatel letöltött db-jéből uj termékek másolása és frissítése tps_webshopba

$result = ata_mysql_query("UPDATE wormsignh_wormsign_hu.tps_webshop pp
INNER JOIN wormsignh_haffner.gigatel w ON(pp.cameron_sku = w.sku)
SET pp.megnevezes=w.cikknev
WHERE w.ts_name=''
");
