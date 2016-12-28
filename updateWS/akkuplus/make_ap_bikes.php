<?php

set_time_limit(1200);

header('Content-Type: text/html; charset=utf-8');
require_once 'init.php';

//batterysarch termékek tábla


try {
    $conn = new PDO($config_ws['connection'], $config_ws['username'], $config_ws['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
header('Content-Type: text/html; charset=utf-8');
/*
function csv_to_array($filename = '', $delimiter = ';') {
    if (!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
            $data[] = $row;
        }
        fclose($handle);
    }
    return $data;
}*/
/*
ata_mysql_query("ALTER TABLE wormsignh_kapacitas.akkuplus CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
ata_mysql_query("set names 'utf8'", $connection);
ata_mysql_query("SET collation_connection = 'utf8_general_ci'");*/
/*
foreach (glob("*.csv") as $filename) {
//$filename = 'gl-batts.csv';    
    $csvLines = csv_to_array($filename);

    if ($csvLines) {
        echo ' ÁrLista: ' . $filename . '<br/>';

        $brand = '';
        foreach ($csvLines as $i => $row) {
            if (0 == $i) {
                continue; // első sor a fejléc, átugorjuk
            }
//            echo 'Kategória: ' . $row[0] . ' | ' . ' Cikkszám: ' . $row[1] . ' Márka: ' . $row[2] . ' <br/>';
//            if ($row[3]) {
//                $brand = $row[3];
//            }
//            $conv = array(" mAh" => "", "mAh" => "", " mm" => "", "mm" => "", "mAH" => "", "MaH" => "");
//
//            $dimension = explode('x', $row[11]);
//            $length = isset($dimension[0]) ? trim($dimension[0]) : '';
//            $width = isset($dimension[1]) ? trim($dimension[1]) : '';
//            $height = isset($dimension[2]) ? trim($dimension[2]) : '';
//
//            $kapac = explode('/', $row[9]);
//            $mah = trim($kapac[0]);
//            $wh = isset($kapac[1]) ? trim($kapac[1]) : '';

            $result = ata_mysql_query(" INSERT IGNORE INTO wormsignh_kapacitas.akkuplus "
                    . " (sku, name, cells, cell_type, volt,serial,parallel,chem, com_batteries)"
                    . " SELECT '" . addslashes($row[0]) . "'"
                    . ", '" . addslashes($row[1]) . "'"
                    . ", '" . addslashes($row[5]) . "'"
                    . ", '" . addslashes($row[6]) . "'"
                    . ", '" . addslashes($row[9]) . "'"
                    . ", '" . addslashes($row[10]) . "'"
                    . ", '" . addslashes($row[11]) . "'"
                    . ", '" . addslashes($row[12]) . "'"
                    . ", '" . addslashes($row[3]) . "'
");
        }
    } else {
        echo 'Hiba: Nem sikerült adatot lekérni!';
    }
}
*/
$categs = array(
    " SET volt = REPLACE(volt, (','), '.')",
    " SET cells_price = '2460'",
    " SET current = '10'",
    " SET used_cells = 'NCR18650PF'",
    " SET fee = '0'",
    " SET charge_voltage = '4.2V' WHERE chem = 'Li-ion'",
    " SET cell_voltage = '3.6V' WHERE chem = 'Li-ion'",
    " SET cells_brand = 'Panasonic'",
    " SET capacity_ah = '2.9'",
    " SET pack_capacity = parallel*capacity_ah",
    " SET new_name = CONCAT('Kerékpár akku felújítás ', com_batteries,' ', chem,' ', volt, 'V ', pack_capacity, 'Ah' )",
    " SET pack_price = (serial*parallel)*(cells_price+fee)",
);

foreach ($categs as $categ) {
    ata_mysql_query(" UPDATE wormsignh_wormsign_hu.akkuplus
                       " . $categ
    );
    echo $categ . '</br>';
}

/*
UPDATE wormsignh_kapacitas.akkuplus
SET volt = REPLACE(volt, (','), '.'),
     SET cells_price = '2460',
     SET current = '10',
     SET used_cells = 'NCR18650PF',
     SET fee = '0',
     SET charge_voltage = '4.2V' WHERE chem = 'Li-ion',
     SET cell_voltage = '3.6V' WHERE chem = 'Li-ion',
     SET cells_brand = 'Panasonic',
     SET capacity_ah = '2.9',
     SET pack_capacity = parallel*capacity_ah,
    SET new_name = CONCAT('Kerékpár akku felújítás ', com_batteries,' ', chem,' ', volt, 'V ', pack_capacity, 'Ah' ),
    SET pack_price = (serial*parallel)*(cells_price+fee)

 * UPDATE wormsignh_wormsign_hu.tps_webshop pp
  INNER JOIN wormsignh_kapacitas.akkuplus w ON (pp.cameron_sku = w.sku)
  SET pp.ar=w.pack_price*1.27
 * 
 *  */
        
$result = ata_mysql_query("SELECT * FROM wormsignh_wormsign_hu.akkuplus");



while ($row = mysql_fetch_assoc($result)) {
    $leiras = "<body>
<p>Elektromos jármű akkumulátor felújítás 1 év garanciával</p>
<p>Specifikáció: </p>
<ul>
  <li>Cella márka: " . addslashes($row['cells_brand']) . "</li>
  <li>Cella típus: " . addslashes($row['used_cells']) . "</li>
  <li>Felhasznált cellák mennyisége: " . addslashes($row['serial']) * addslashes($row['parallel']) . " db</li>
  <li>Kötés: " . addslashes($row['serial']) . "S" . addslashes($row['parallel']) . "P </li>
  <li>Névleges feszültség:" . addslashes($row['volt']) . "V</li>
  <li>Tényleges feszültség: " . addslashes($row['serial']) * addslashes($row['cell_voltage']) . "V</li>
  <li>Töltőfeszültség: " . addslashes($row['serial']) * addslashes($row['charge_voltage']) . "V</li>
  <li>Az új pakk kapacitása: " . addslashes($row['pack_capacity']) . "Ah</li>
  <li>Maximális folyamatos áramleadás: " . addslashes($row['current']) * addslashes($row['parallel']) . "A</li>
  <li>Kémiai összetétel: " . addslashes($row['chem']) . "</li>
</ul>
<p>Tudnivalók az akkumulátor felújításról:</p>
<p>A felújítás során az Ön által hozzánk eljuttatott akkumulátorból az összes cellát kicseréljük a fent leírtak alapján új cellákra.</p>
<p>Az így kapott új akkupakk kapacitása " . addslashes($row['pack_capacity']) . "Ah lesz, maximálisan " . addslashes($row['current']) * addslashes($row['parallel']) . "A áram leadására képes folyamatos üzem mellett.</p>
<p>A fenti paraméterek tájékoztató jellegűek, bizonyos esetekben előfordulhatnak eltérések, így minden esetben a végső árajánlatot a leadott akkumulátor bevizsgálása után adunk</p>
<p>A leírás tájékoztató jellegű, nem minősül ajánlattételnek! Pontos árajánlatot kizárólag a konkrét akkumulátorpakk teljesen pontos ismeretében tudunk.</p>
<p>A felújítani kívánt akkumulátort hozza el, vagy küldje el hozzánk. Ezt követően kötelezettségmentesen írásos árajánlatot adunk a teljes felújításra</p>
<p>Információink szerint a következő akkumulátorok is a fenti paraméterekkel felújíthatók:</p>
<p>Kompatibilis lista: " . addslashes($row['com_batteries']) . "</p>
</body>";

    ata_mysql_query("
  UPDATE wormsignh_wormsign_hu.akkuplus
  SET description_hu = '" . addslashes($leiras) . "' WHERE sku = '" . addslashes($row['sku']) . "'");
};

echo $leiras;


