<?php

for ($i = 1; $i <= 391; $i++) {
    $xmldata = file_get_contents('p/Products_' . $i . '.xml');
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://kereso.wormsign.hu/update/products/products_symbol_1.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('xmldata' => strtr($xmldata, array('utf-16' => 'utf-8')))));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    file_put_contents('o/o' . $i, $server_output);
    
    curl_close($ch);
}
