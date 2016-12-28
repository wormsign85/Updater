<?php
$link = mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("wormsignh_wormtest");

echo 'Connected successfully'.'</br>';

  
mysql_query("set names 'utf8'", $link); 

//RP nagyker árak kalkulációja: 3-as kategoria a liegkisebb kedvezményt jelenti, 
//fokozatosan érhető el az első árkategória ami a legjobb árakat jelejnti ami elérhető, 
//vagyis a legnagyobb kedvezményt!!
// a kalkuláció a következő: a legkisebb kedvezmény mértéke 20%, de minimum a beszerzési ár + 35%
//A 2-es kedvezmény a kisker árból 35%, de minimum a beszerzési árra 35% árrést rakunk rá
//az 1-es kedvezmény a kisker árból 40%, de minimum a beszer árra 35% árrést jelent.




mysql_query("
update wormsignh_wormtest.beszer_arak pp
inner join wormsignh_wormtest.all_prices w ON(pp.cameron_sku=w.sku)
set pp.ar_usd=w.price",
 $link);

    echo 'Beszer �rak friss�tve' . '<br/>';

mysql_query("
update wormsignh_wormtest.beszer_arak pp
inner join wormsignh_wormtest.all_prices w ON(pp.cameron_sku=w.sku)
set pp.beszerar=w.price_huf",
 $link);

    echo 'Beszer �rak friss�tve' . '<br/>';
    
mysql_query("
update wormsignh_wormsign_hu.tps_webshop pp
inner join wormsignh_wormtest.beszer_arak w ON(pp.cameron_sku=w.cameron_sku)
set pp.beszerar=w.beszerar",
 $link);

    echo 'Beszer �rak friss�tve tps_webshop' . '<br/>';
    
    mysql_query("
update wormsignh_wormtest.tps_webshop_feltolt pp
inner join wormsignh_wormtest.beszer_arak w ON(pp.cameron_sku=w.cameron_sku)
set pp.beszerar=w.beszerar",
 $link);

    echo 'Beszer �rak friss�tve' . '<br/>';



mysql_query("
update wormsignh_wormtest.beszer_arak
set beszerar=((ar_usd*arfolyam+cn)*1.1+termekdij)*1.1",
 $link);

    echo 'Beszer �rak sz�mol�sa' . '<br/>';




mysql_query("
update beszer_arak pp
inner join tps_webshop_feltolt w ON(pp.szla_id=w.szla_id)
set pp.cameron_sku=w.cameron_sku",
 $link);

    echo 'cameronsku beszerarakban friss�tve' . '<br/>';

mysql_query("
update beszer_arak pp
inner join cameron_ar w ON(pp.cameron_sku=w.cameron_sku)
set pp.ar_usd=w.ar",
 $link);

    echo '�rak friss�t�se beszer_arak USD-ban cameron_ar-b�l' . '<br/>';





mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set rp_1=greatest((beszerar*1.27)/0.65,ar*0.65)",
 $link);

    echo 'Az 1-es kedvezmény a kisker árból 35%, de minimum a beszerzési ár + 35%' . '<br/>';

mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set rp_2=greatest((beszerar*1.27)/0.65,ar*0.72)",
 $link);

    echo 'A 2-es kedvezmény a kisker árból 28%, de minimum a beszerzési ár + 35%' . '<br/>';

mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set rp_3=greatest((beszerar*1.27)/0.65,ar*0.8)",
 $link);

    echo 'A 3-as kedvezmény mértéke 20%, de minimum a beszerzési ár + 35%' . '<br/>';


//mobil akkuk 1-es kategoria
mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set rp_1=greatest((beszerar*1.27)/0.6,ar*0.5)
WHERE category IN(1911,1912,1561,1645,1646,
1733,1927,1644,1743,1832,1847,1910,1903,
1926,1925,1923,1921,1920,1919,1918,1917,
1916,1915,1914,1913,3116,3118,3119,3120,3164,
3121)",
 $link);

    echo 'Az 1-es kedvezmény mobil akkukhoz!! a kisker árból 50%, de minimum a beszerzési ár + 40%' . '<br/>';

//mobil akkuk 2-es kategoria
mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set rp_2=greatest((beszerar*1.27)/0.6,ar*0.65)
WHERE category IN(1911,1912,1561,1645,1646,
1733,1927,1644,1743,1832,1847,1910,1903,
1926,1925,1923,1921,1920,1919,1918,1917,
1916,1915,1914,1913,3116,3118,3119,3120,3164,
3121)",
 $link);

    echo 'Az 2-es kedvezmény mobil akkukhoz!! a kisker árból 35%, de minimum a beszerzési ár + 40%' . '<br/>';

//mobil akkuk 3-As kategoria
mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set rp_3=greatest((beszerar*1.27)/0.6,ar*0.7)
WHERE category IN(1911,1912,1561,1645,1646,
1733,1927,1644,1743,1832,1847,1910,1903,
1926,1925,1923,1921,1920,1919,1918,1917,
1916,1915,1914,1913,3116,3118,3119,3120,3164,
3121)",
 $link);

    echo 'Az 3-as kedvezmény mobil akkukhoz!! a kisker árból 35%, de minimum a beszerzési ár + 40%' . '<br/>';

mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set rp_3='',rp_2='',rp_1=''
where beszerar=''",
 $link);

    echo 'nem RP árak nullázása' . '<br/>';


mysql_query("
update wormsignh_wormsign_hu.tps_webshop
set robi=(beszerar*1.35)*1.27
",
 $link);

    echo 'robi �rai' . '<br/>';

mysql_query("
update wormsignh_wormsign_hu.tps_webshop pp
inner join wormsignh_wormsign_hu.tps_webshop_categories w ON(pp.category=w.id)
set pp.keszlet='off'
WHERE w.raktarkezeles='0'
",
 $link);

    echo 'rakt�rkezel�s' . '<br/>';

mysql_query("
update wormsignh_wormsign_hu.tps_webshop pp
inner join wormsignh_wormsign_hu.tps_webshop_categories w ON(pp.category=w.id)
set pp.vasarolhato_ha_nincs_raktaron=w.vasarolhato_ha_nincs_raktaron",
 $link);

    echo 'vasarolhato_ha_nincs_raktaron' . '<br/>';

/*
mysql_query("
update tps_webshop
set kiegeszito_termekek='201503166'
Where feszultseg='12'
AND category IN(1584,1582,1583,1585,1572,
1350,1573,1587,1650,1704,1688,1689,1721,
1617,1600,1601,1581,1588,1589,1618,1619,1608)
AND osszetetel='Ni-Mh'",
 $link);

    echo '12V Nimh kiegeszito termekek' . '<br/>';
*/
