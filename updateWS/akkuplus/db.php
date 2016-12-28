<?php

//function mysqli($config_db) {
//    $conn = mysql_connect('localhost', 'root', '');
//    if (!$conn) {
//        die('Could not connect: ' . mysql_error());
//    }
//    echo'Connected successfully <br/>';
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

function ata_get_insert($table, $array, $ignore = false) {
	$insert_text = "INSERT" . ($ignore ? ' IGNORE' : '') . " INTO " . $table;
	$keys = array();
	$values = array();
	foreach ($array as $k=>$v) {
		$keys[] = $k;
		$values[] = $v;
	}
	$key_string = "(";
	foreach ($keys as $key) {
		$key_string = $key_string . $key . ", ";
	}
	$key_string = substr($key_string, 0, -2);
	$insert_text = $insert_text . " " . $key_string . ")";;
	$insert_text = $insert_text . " VALUES ";
	$value_string = "(";
	foreach ($values as $value) {
		if (null !== "string") {
			$value_string = $value_string . "'" . $value . "', ";
		}
		else {
			$value_string = $value_string . "NULL, ";
		}
	}
	$value_string = substr($value_string, 0, -2);
	$insert_text = $insert_text . $value_string . ")";
	return $insert_text;
}