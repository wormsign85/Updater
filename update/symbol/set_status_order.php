<?php

$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("mydb");

echo 'Connected successfully'.'</br>';

  
mysql_query("set names 'utf8'", $link); 

mysql_query("
UPDATE orders 
SET symbol_status='1'",
 $link);
