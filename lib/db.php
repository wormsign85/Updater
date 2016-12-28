<?php

//function mysqli($config_db) {
//    $conn = mysql_connect('localhost', 'wormsignh_worm', 'IxOn1985');
//    if (!$conn) {
//        die('Could not connect: ' . mysql_error());
//    }
//   // echo'Connected successfully <br/>';
//
//    return $conn;
//}
//
//$connection = mysqli($config_db);


function ata_mysql_query($sql, $link = null) {
    if ($link) {
        $result = mysql_query($sql, $link);
    } else {
        $result = mysql_query($sql);
    }
    if (!$result) {
        die('SQL hiba: ' . mysql_error() . ' SQL: ' . $sql);
    }
    return $result;
}
