<?php


$initfile = __DIR__ . '/../lib/init.php';
if (file_exists($initfile)) {
    // lokális
    require $initfile;

    $user = 'root';
    $pass = '';
} else {
    // éles
    require __DIR__ . '/../lib/init.php';

    $user = 'wormsignh_worm';
    $pass = 'IxOn1985';

//    require_once '../orders/get_orders.php';
//
//    require_once '../customers/get_customers.php';
}


try {
    $conn = new PDO($config_db_stock['connection'], $config_db_stock['username'], $config_db_stock['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}


$filename = 'stocks_' . md5(uniqid(true) . rand(1, 999)) . '.xml';
file_put_contents($filename, $_POST['xmldata']);
$xml = file_get_contents($filename);

////file_put_contents('stock.xml', $_POST['xmldata']);
////exit;
//$xml = file_get_contents('stocks_*.xml');
//$xml = $_POST('xmldata');

//először feltöltünk minden terméke symbolból, majd csak azokat aminek változik a készlete.
//ezt néha ujra meg kell csinálni, üríteni az egészet és megint feltölteni az egészet

$stocks = new SimpleXMLElement($xml);

$sqlutf = "set names 'utf8'";
$sth = $conn->prepare($sqlutf);
$statement = $sth->execute();

$sql = " UPDATE full_stock SET warehouse=:warehouse,warehousename=:warehousename,warehousesite=:warehousesite,"
        . " product=:product,quantity=:quantity,strictallocate=:strictallocate,"
        . " nonstrictallocate=:nonstrictallocate WHERE productcode=:productcode";


foreach ($stocks->ProductQuantity as $sorszam => $stock) {
    $warehouse = $stock->Warehouse;
    $warehousename = $stock->WarehouseName;
    $warehousesite = $stock->WarehouseSite;
    $product = $stock->Product;
    $productcode = $stock->ProductCode;
    $quantity = $stock->Quantity;
    $strictallocate = $stock->StrictAllocate;
    $nonstrictallocate = $stock->NonStrictAllocate;

    //Csak a központi raktárból tölthetünk fel készletet
    
    if ($warehousename = 'Központi raktár') {
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
}


echo 'OK';


require_once 'update_stock.php';
//require_once '../stock/unas/upload_stock.php';





