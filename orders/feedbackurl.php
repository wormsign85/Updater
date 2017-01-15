<?php
$id= $_GET['id'];
$text= $_GET['text'];
//új oszlop, symbol_status 0 és 1 a feldolgozás visszajelzéséhez
//
file_put_contents('order_sync.log',date('Y-m-d H:i:s') . " " . $id . " " . $text 
        . "\n",FILE_APPEND);
