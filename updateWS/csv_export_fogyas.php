<?php
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=keszlet.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Cikkszám','Termék Név','Cameron cikkszám','Mennyiség'));

// fetch the data
mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
mysql_select_db('wormsignh_mydb');
$rows = mysql_query('SELECT Item_Sku,Item_name,cameron_sku,Item_quantity FROM orders_items');

// loop over the rows, outputting them
while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);