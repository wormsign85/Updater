<?php
$id= $_GET['errorid'];
$text= $_GET['text'];
//új oszlop, symbol_status 0 és 1 a feldolgozás visszajelzéséhez
//
file_put_contents('customer_error.log',date('Y-m-d H:i:s') . " " . $id . " " . $text .
        "\n",FILE_APPEND);
