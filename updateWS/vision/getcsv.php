<?php

require_once 'init.php';


function csv_to_array($filename = '', $delimiter = ';') {
    if (!file_exists($filename) || !is_readable($filename))
        return false;

    $header = null;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== false) {
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $data[] = $row;
        }
        fclose($handle);
    }

    return $data;
}

function csv_to_array_assoc($filename = '', $delimiter = ';') {
    if (!file_exists($filename) || !is_readable($filename))
        return false;

    $header = null;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== false) {
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            // Ha még nincs fejléc, akkor első sor és fejléc adatok felolgozása
            if (null === $header) {
                $header = array();
                foreach ($row as $i => $cell) {
                    $header[$i] = $cell;
                }
            } else {
                $row_assoc = array();
                foreach ($row as $i => $cell) {
                    $header_name = $header[$i];
                    $row_assoc[$header_name] = $cell;
                }
                $data[] = $row_assoc;
            }
        }
        fclose($handle);
    }

    return $data;
}