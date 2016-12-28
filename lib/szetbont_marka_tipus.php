<?php

function szetbontMarkaEsTipus($markaestipus) {
    $markak = array(
        'Huawei',
        'Nokia',
        'Samsung',
        'Sony Ericsson',
        'Sony',
        'Apple',
        'LG',
        'Acer',
        'Alcatel',
        'Apple',
        'ASUS',
        'BlackBerry',
        'HTC',
        'Huawei',
        'Lenovo',
        'LG',
        'Microsoft',
        'Motorola',
        'Telenor',
        'Vodafone',
        'Wayteq',
        'ZTE',
        'AKAI',
        'Apollo',
        'ARCHOS',
        'BeeX',
        'Blaupunkt',
        'Bluepanther',
        'Colorovo',
        'ConCorde',
        'DPS',
        'Fujitsu',
        'Funscreen',
        'GoClever',
        'HP',
        'Kindle',
        'KOOBE',
        'Maxell',
        'Microsoft',
        'Modecom',
        'MyAudio',
        'MyPhone',
        'Navon',
        'Overmax',
        'Pentagram',
        'Point of View',
        'Prestigio',
        'Toshiba',
        'Xiaomi',
        'SAMSUNG',
        'NOKIA',
        'SAGEM',
        'Sagem'
    );

    $tipus = false;
    foreach ($markak as $marka) {
        $markaesspace = $marka . ' ';
        $tmptipus = explode($markaesspace, $markaestipus);
        if ($tmptipus[0] == '') {
            $tipus = trim($tmptipus[1]);
            break;
        }
    }

    if ($tipus) {
        return array(
            'marka' => $marka,
            'tipus' => $tipus
        );
    } else {
        return null;
    }
}
