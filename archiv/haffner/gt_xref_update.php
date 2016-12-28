<?php

SET_time_limit(1200);
header('Content-Type: text/html; charSET=utf-8');
require_once 'lib/init.php';


$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=xref
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy S IV. mini (GT-I9195)'),'Samsung Galaxy S4 Mini I9195')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,(' Samsung Galaxy S II. I9100'),'Samsung Galaxy S2')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy S IV. mini (GT-I9190)'),'Samsung Galaxy S4 Mini I9190')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy S IV. (GT-I9500)'),'Samsung Galaxy S4 I9500')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy S V. (SM-G900)'),'Samsung Galaxy S5 SM-G900')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy S V. mini (SM-G800)'),'Samsung Galaxy S5 Mini SM-G800')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy A3 (SM-A300F)'),'Samsung Galaxy A3 SM-A300F')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy Ace 4 LTE (SM-G357FZ)'),'Samsung Galaxy Ace 4 SM-G357FZ')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy S6 (SM-G920)'),'Samsung Galaxy S6 SM-G920')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy S6 EDGE (SM-G925F)'),'Samsung Galaxy S6 SM-G925')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy EDGE + (SM-G928)'),'Samsung Galaxy S6 EDGE + SM-G928')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy EDGE + (SM-G928)'),'Samsung Galaxy S6 EDGE + SM-G928')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy Young 2 (SM-G130)'),'Samsung Galaxy Young 2 SM-G130')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Samsung Galaxy S V. Active SM-G870'),'Samsung Galaxy S5 Active SM-G870')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 5C' WHERE xref=' Apple iPhone 5C'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 6S' WHERE xref=' Apple iPhone 6S 4.7``'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 6S Plus' WHERE xref=' Apple iPhone 6S Plus 5.5``'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 6S Plus' WHERE xref='Apple iPhone 6S Plus 5.5``'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 6S' WHERE xref='Apple iPhone 6S 4.7``'
  ");


$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 6' WHERE xref='Apple iPhone 6 4.7``'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 6' WHERE xref=' Apple iPhone 6 4.7``'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 6 Plus' WHERE xref=' Apple iPhone 6 Plus 5.5``'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 6 Plus' WHERE xref='Apple iPhone 6 Plus 5.5``'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 5/5S' WHERE xref=' Apple iPhone 5'
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 5/5S' WHERE xref=' Apple iPhone 5S'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 4/4S' WHERE xref=' Apple iPhone 4'
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 4/4S' WHERE xref=' Apple iPhone 4S'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 5/5S' WHERE xref='Apple iPhone 5'
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 5/5S' WHERE xref='Apple iPhone 5S'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 4/4S' WHERE xref='Apple iPhone 4'
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new='Apple iPhone 4/4S' WHERE xref='Apple iPhone 4S'
  ");


$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Apple IPAD'),'Apple IPad')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new = REPLACE(xref_new, ('Samsung Galaxy Core Plus'), 'Samsung Galaxy Core Plus G350')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('Apple IPAD'),'Apple IPad')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new = REPLACE(xref_new, ('Samsung Galaxy S III. (I9300)'), 'Samsung Galaxy S3 I9300')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new = REPLACE(xref_new, ('Samsung Galaxy S IV. Active (I9295)'), 'Samsung Galaxy S4 Active I9295')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new = REPLACE(xref_new, ('Samsung Galaxy S6 SM-G925'), 'Samsung Galaxy S6 Edge SM-G925')
  ");
$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new = REPLACE(xref_new, ('Samsung Galaxy Express 2'), 'Samsung Galaxy Express 2 G3815')
  ");
      

$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel
SET uj_kategoria='MOBIL_Akkumulátorok' WHERE cikkcsoport LIKE '%akkumulátor%'
  ");

$result = ata_mysql_query("UPDATE wormsignh_haffner.gigatel
SET new_subcategory=cikkfajta WHERE cikkcsoport LIKE '%akkumulátor%'
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('S IV.'),'S4')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('S V.'),'S5')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('S VI.'),'S6')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('S III.'),'S3')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('mini'),'Mini')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('GT-'),'')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('('),'')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,(')'),'')
  ");

$result = ata_mysql_query("update wormsignh_haffner.gigatel_xref
set xref_new=REPLACE(xref_new,('II.'),'2')
  ");



echo 'xref update kész';

//require 'xref_make_new_compatible_gt.php';

//      update gigatel
//set gyarto=REPLACE(gyarto,("CASE-MATE"),"Case-Mate")
//        update gigatel
//set gyarto=REPLACE(gyarto,("ADIDAS"),"Adidas")
//            update gigatel
//set gyarto=REPLACE(gyarto,("BASEUS"),"Baseus")
//    
//                        update gigatel
//set gyarto=REPLACE(gyarto,("BLAUTEL"),"Blautel")
//    
//                        update gigatel
//set gyarto=REPLACE(gyarto,("BUGATTI"),"Bugatti")
//            update gigatel
//set gyarto=REPLACE(gyarto,("GRIFFIN TECHNOLOGY"),"Griffin Technology")
//            update gigatel
//set gyarto=REPLACE(gyarto,("IGLOW"),"IGlow")
//    
//                        update gigatel
//set gyarto=REPLACE(gyarto,("KRUSELL"),"Krusell")
//            update gigatel
//set gyarto=REPLACE(gyarto,("MERCURYCASE"),"MercuryCase")
//            update gigatel
//set gyarto=REPLACE(gyarto,("MOMAX"),"Momax")
//            update gigatel
//set gyarto=REPLACE(gyarto,("ROCKPHONE"),"RockPhone")
//            update gigatel
//set gyarto=REPLACE(gyarto,("TAKEFANS"),"TakeFans")
//            update gigatel
//set gyarto=REPLACE(gyarto,("USAMS"),"Usams")
//            update gigatel
//set gyarto=REPLACE(gyarto,("gigapack"),"GigaPack")
//    
          
//require_once 'xref_make_new_compatible_gt.php';

/*
//xref átmásolása product_xref-be haffnerből
$result = ata_mysql_query("insert ignore into wormsignh_haffner.product_xref
(id,sku,xref,xref_new,szla_id,stock,name,category,letrehozva,haszonkulcs)
SELECT id,sku,xref,xref_new,szla_id,stock,name,category,letrehozva,haszonkulcs
FROM wormsignh_haffner.haffner_xref WHERE szla_id!=''
  ");

//xref átmásolása product_xref-be gigatelből
$result = ata_mysql_query("insert ignore into wormsignh_haffner.product_xref
(id,sku,xref,xref_new,szla_id,stock,name,category,letrehozva,haszonkulcs)
SELECT id,sku,xref,xref_new,szla_id,stock,name,category,letrehozva,haszonkulcs
FROM wormsignh_haffner.gigatel_xref WHERE szla_id!=''
  ");
*/
//product_xref frissítése
//$result = ata_mysql_query("UPDATE wormsignh_haffner.product_xref pp
//    INNER JOIN wormsignh_haffner.gigatel_xref w ON(w.sku=pp.sku)
//    set pp.xref_new=w.xref_new
//  ");
//
////product_xref frissítése
//$result = ata_mysql_query("UPDATE wormsignh_haffner.product_xref pp
//    INNER JOIN wormsignh_haffner.haffner_xref w ON(w.sku=pp.sku)
//    set pp.xref_new=w.xref_new 
//  ");


        
