<?php


require_once '../lib/init.php';

//require_once 'lib/init.php';
set_time_limit(600);
//connect to the database 

# Include the Dropbox SDK libraries
require_once "dropbox-sdk/Dropbox/autoload.php";
use \Dropbox as dbx;

$appInfo = dbx\AppInfo::loadFromJsonFile(dirname(__FILE__) . '/config.json');
$webAuth = new dbx\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");

// $authorizeUrl = $webAuth->start();

// $authCode = 'xGlodZnuvioAAAAAAAAAAeomQe6HquIKK4KMe7Xzezo';
$accessToken = 'V6XVvuVhmwEAAAAAAAEEF4XjqtRHe1UwCfpn8DTLhPMPglfwwzEaXq357YDFZcvJ';
// $dropboxUserId = '4831724';
// $webAuth->finish($authCode);

$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");
$accountInfo = $dbxClient->getAccountInfo();

$localPath = dirname(__FILE__) . "/../../termekek.csv";
$dropboxPath = "/Tokotveszek/termekek.csv";
$metadata = $dbxClient->getFile($dropboxPath, $f = fopen($localPath, "wb"));
if ($metadata === null) {
    fwrite(STDERR, "File not found on Dropbox.\n");
    die;
}
fclose($f);

print_r($metadata);
echo "File contents written to \"$localPath\"\n"; 

$szlaidfilename = $site_path."tmpfiles/tmpar.csv";

if(is_uploaded_file($HTTP_POST_FILES['upfile']['tmp_name'])) {
	move_uploaded_file($HTTP_POST_FILES['upfile']['tmp_name'], $szlaidfilename);
	chmod($szlaidfilename,0777);
} elseif ($fileContent = file_get_contents(dirname(__FILE__) . '/../../termekek.csv')) {
    file_put_contents($szlaidfilename, $fileContent);
} else
{
    echo 'Something went wrong!';
    exit;
}


try {
    $conn = new PDO($config_db['connection'], $config_db['username'], $config_db['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
//
//$connect = mysql_connect("localhost", $config_db['username'], $config_db['password']);
//mysql_select_db("wormsignh_kapacitas", $conn); //select the table
$csv = file_get_contents('termekek.csv');
header('Content-Type: text/html; charset=utf-8');

//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, "http://haffner24.hu/prod_short.php?id=522&pass=b9d3f1256139bb019736e0025d1dcd0d");
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_HEADER, false);
//curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
//$csv = curl_exec($ch);
//curl_close($ch);



$pricelist_tok_db = 'wormsignh_haffner';

ata_mysql_query("set names 'utf8'", $connection);

$sql_delete_product_discounts = "DELETE FROM " . $pricelist_tok_db . ".akcios_arak";
$q = $conn->prepare($sql_delete_product_discounts);
$q->execute();

//$sku_conv = array("/" => "-", "," => "-", "+" => "-");

//$csv = fopen($szlaidfilename, "r");



if ($csv) {

//get the csv file
    $csvLines = str_getcsv($csv, "\n"); //parse the rows
    foreach ($csvLines as $i => $line) {
        if (0 == $i) {
            continue; // első sor a fejléc, átugorjuk
        }
        $row = str_getcsv($line, ";");
        echo '<b>Cikkszám:</b> ' . $row[0] . ' | ' . '</br><b>Kedvezmény:</b>' . $row[1] . '</br><b>Akció kezdet:</b>' . $row[2] . 'Ft <br/></br>';



        $result = ata_mysql_query("REPLACE INTO " . $pricelist_tok_db . ".akcios_arak "
                . "(sku, discount, kezdet)"
                . " SELECT '" . addslashes($row[0]) . "'"
                . " ,'" . addslashes($row[1]) . "'"
                . ", '" . addslashes($row[2]) . "'
");
    }
} else {
    echo 'Hiba: Nem sikerült adatot lekérni!';
}

echo 'Egyedi kategória kedvezmények beimportálása <br/>';
