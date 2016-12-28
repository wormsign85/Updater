<?php

$user = 'wormsignh_worm';
$pass = 'IxOn1985';

try {
    $conn = new PDO('mysql:host=localhost;dbname=wormsignh_update', $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
file_put_contents('stock.xml', $_POST['xmldata']);
//exit;
$xml = file_get_contents('stock.xml');
//$xml = $_POST('xmldata');

$stocks = new SimpleXMLElement($xml);

$sqlutf = "set names 'utf8'";
$sth = $conn->prepare($sqlutf);
$statement = $sth->execute();

$sql_delete_stocks = "DELETE FROM wormsignh_update.stock";
$q = $conn->prepare($sql_delete_stocks);
$q->execute();

$sql = "REPLACE INTO stock SET warehouse=:warehouse,warehousename=:warehousename,warehousesite=:warehousesite,"
        . " product=:product,productcode=:productcode,quantity=:quantity,strictallocate=:strictallocate,"
        . " nonstrictallocate=:nonstrictallocate";

$sql_update_stock = "UPDATE wormsignh_wormtest.tps_webshop_feltolt pp "
        . "INNER JOIN wormsignh_update.stock w ON(w.productcode=pp.szla_id)"
        . "SET pp.keszlet=w.free_stock";

$sql_free_stock = "UPDATE stock"
        . "SET free_stock=(quantity-nonstrictallocate)";

foreach ($stocks->ProductQuantity as $sorszam => $stock) {
    $warehouse = $stock->Warehouse;
    $warehousename = $stock->WarehouseName;
    $warehousesite = $stock->WarehouseSite;
    $product = $stock->Product;
    $productcode = $stock->ProductCode;
    $quantity = $stock->Quantity;
    $strictallocate = $stock->StrictAllocate;
    $nonstrictallocate = $stock->NonStrictAllocate;
    $q = $conn->prepare($sql);
    $q->execute(array(
        ':warehouse' => $warehouse,
        ':warehousename' => $warehousename,
        ':warehousesite' => $warehousesite,
        ':product' => $product,
        ':productcode' => $productcode,
        ':quantity' => $quantity,
        ':strictallocate' => $strictallocate,
        ':nonstrictallocate' => $nonstrictallocate
    ));
}
echo 'OK';

require_once 'update_stock.php';
require_once 'stock_xml_to_unas.php';
require_once 'stock_xml_to_unas_rp.php';

