<?php

set_time_limit(6000);
require_once 'lib/init.php';
require_once 'lib/szetbont_marka_tipus.php';

$sql = "SELECT sku, xref_new FROM wormsignh_haffner.haffner_xref WHERE sku='NSY037'";

function szetbont($s) {
    $tmp = preg_split('/[\s*]{2,}/', $s);
    //$tmp = explode(',', $s);

    $ret = array();
    foreach ($tmp as $t) {
        if (trim($t)) {
            $ret[] = $t;
        }
    }

    return $ret;
}

function ekezet_eltavolitas($s) {
    $ret = strtr($s, array(
        ' ' => '-', 'ö' => 'o',
        'Ö' => 'o', 'ü' => 'u',
        'Ü' => 'u', 'ó' => 'o',
        'Ó' => 'o', 'Ő' => 'o',
        'ő' => 'o', 'ú' => 'u',
        'Ú' => 'u', 'é' => 'e',
        'É' => 'e', 'á' => 'a',
        'Á' => 'a', 'ű' => 'u',
        'ű' => 'u', 'í' => 'i',
        'Í' => 'i',
    ));

    return $ret;
}

ata_mysql_query("set names 'utf8'", $connection);
header('Content-Type: text/html; Charset=utf-8');

$filterOszlopok = array();
$result = ata_mysql_query("SELECT sql_name, param_id FROM wormsignh_haffner.product_param WHERE param_name != ''");
while ($row = mysql_fetch_assoc($result)) {
    $filterOszlopok[$row['sql_name']] = $row['param_id'];
}

$csv = array();

$map = array();
$result = ata_mysql_query("SELECT sku, xref_new FROM wormsignh_haffner.haffner_xref WHERE xref_new = 'Apple iPhone 5/5S'");
while ($row = mysql_fetch_assoc($result)) {
    $markaestipus = $row['xref_new'];
    $markaestipusarray = szetbontMarkaEsTipus($markaestipus);
    if ($markaestipusarray) {
        $tipus = $markaestipusarray['tipus'];
        $categ_sql = "SELECT c2.* FROM wormsignh_haffner.categories
        c1 INNER JOIN wormsignh_haffner.categories c2 ON (c1.cat_id = c2.main_cat_id) WHERE c1.cat_name = '" . addslashes($tipus) . "'";

        $resultCateg = ata_mysql_query($categ_sql);
        $categ = mysql_fetch_assoc($resultCateg);
        if ($categ) {
            $categid = $categ['cat_id'];
            // echo $categ['cat_name'] . '<br>';

            $xrefResult = ata_mysql_query("SELECT DISTINCT x2.sku FROM wormsignh_haffner.haffner_xref x1"
                    . " INNER JOIN wormsignh_haffner.haffner_xref x2 ON (x1.xref_new=x2.xref_new AND x2.xref_new='" . addslashes($markaestipus) . "')"
                    . " WHERE x1.xref_new='" . addslashes($markaestipus) . "'");
            $skus = array();
            while ($rowXref = mysql_fetch_assoc($xrefResult)) {
                $skus [] = "'" . addslashes($rowXref['sku']) . "'";
            }

            if ($skus) {
                $filterek = array();
                $prodResult = ata_mysql_query("SELECT p.* FROM wormsignh_haffner.products p"
                        . " WHERE p.sku IN (" . implode(',', $skus) . ")");

                while ($rowProd = mysql_fetch_assoc($prodResult)) {
                    $alt_category_parts = explode('|', $rowProd['alt_cat']);
                    if (isset($alt_category_parts[3])) {
                        $lastCateg = $alt_category_parts[3];
                        $categ_sql2 = "SELECT c1.* FROM wormsignh_haffner.categories c1"
                                . " INNER JOIN wormsignh_haffner.categories c2 ON (c1.main_cat_id=c2.cat_id AND c2.cat_name='" . addslashes($alt_category_parts[2]) . "') WHERE c1.cat_name='" . addslashes($lastCateg) . "'";
                        $resultCateg2 = ata_mysql_query($categ_sql2);
                        $categ2 = mysql_fetch_assoc($resultCateg2);
                        if ($categ2) {
                            $url = 'http://www.akkucentral.hu/spl/' . $categ2['cat_id'] . '/' . ekezet_eltavolitas($categ2['cat_name']);
                            foreach ($filterOszlopok as $filterOszlop => $filterId) {
                                if (!empty($rowProd[$filterOszlop])) {
                                    $filterUrl = $filterId . ':' . urlencode($rowProd[$filterOszlop]);
                                    $keszUrl = $url . '?filter=' . $filterUrl;
                                    // echo $keszUrl . '<br>';
                                    $ertekek = array(
                                        $alt_category_parts[1],
                                        $alt_category_parts[2],
                                        $alt_category_parts[3],
                                        $filterOszlop,
                                        $keszUrl
                                    );
                                    $sor = implode(';', $ertekek);
                                    $csv[$sor] = 1;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

$csv = implode("\n", array_keys($csv));
file_put_contents('iphone5.csv', $csv);
