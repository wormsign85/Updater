<?php


require_once 'init.php';

ata_mysql_query("set names 'utf8'", $connection);

function szetbontTulajdonsagok($tulajdonsagok) {
    $tulajdonsag_lista = array(
        //Nyitás
        'oldalra nyitható' => 'kivitel:oldalra nyíló',
        'oldalra nyíló' => 'kivitel:oldalra nyíló',
        'kártyatartóval' => 'kivitel:kártyatartós',
        'lenyitható' => 'kivitel:lenyitható',
        'képernyővédő fóliával' => 'tartozek:védőfóliával',
        'hívás mutatóval' => 'kivitel:Hívás mutatóval',
        //Telefon Típusok APPLE
        'Apple iPhone 5C' => 'compatible_device',
        'Apple iPhone 6 Plus' => 'compatible_device',
        'Apple iPhone 6' => 'compatible_device',
        'Apple iPhone 5 / 5S' => 'compatible_device:Apple iPhone 5/5S',
        'Apple iPhone 5/5S' => 'compatible_device',
        'Apple iPhone 4 / 4S' => 'compatible_device:Apple iPhone 4/4S',
        'Apple iPhone 4/4S' => 'compatible_device',
        'Apple iPhone 5C' => 'compatible_device',
        'Apple iPad Mini/iPad Mini 2' => 'compatible_device',
        'Apple iPad Mini/iPad Mini 2/iPad Mini 3' => 'compatible_device',
        'Apple iPad Air 2' => 'compatible_device',
        //Telefon Típusok Samsung
        'Samsung Galaxy S6 SM-G920' => 'compatible_device:Samsung Galaxy S6',
        'Samsung Galaxy Note Edge SM-N915' => 'compatible_device:Samsung Galaxy Note Edge',
        'Samsung Galaxy A3 SM-A300F' => 'compatible_device:Samsung Galaxy A3',
        'Samsung Galaxy A5 SM-A500F' => 'compatible_device:Samsung Galaxy S5',
        'Samsung Galaxy J5 SM-J500F' => 'compatible_device:Samsung Galaxy J5',
        'Samsung Galaxy S5 Mini SM-G800' => 'compatible_device:Samsung Galaxy S5 Mini',
        'Samsung Galaxy Note 4 SM-N910C' => 'compatible_device:Samsung Galaxy Note 4',
        'Samsung Galaxy Note 3 N9000' => 'compatible_device:Samsung Galaxy Note 3',
        'Samsung Galaxy S4 i9500' => 'compatible_device:Samsung Galaxy S4',
        'Samsung Galaxy S6 Edge SM-G925' => 'compatible_device:Samsung Galaxy S6 Edge',
        'Samsung Galaxy Alpha SM-G850' => 'compatible_device:Samsung Galaxy Alpha',
        'Samsung Galaxy S4 Mini i9190' => 'compatible_device:Samsung Galaxy S4 Mini',
        'Samsung Galaxy S Duos S7562' => 'compatible_device:Samsung Galaxy S Duos',
        'Samsung Galaxy Core i8260' => 'compatible_device:Samsung Galaxy Core LTE',
        'Samsung Galaxy Grand 2 G7100' => 'compatible_device:Samsung Galaxy Grand 2',
        'Samsung Galaxy Core Plus G350' => 'compatible_device:Samsung Galaxy Core Plus',
        'Samsung Galaxy Ace 4 LTE SM-G357FZ' => 'compatible_device:Samsung Galaxy Ace 4',
        'Samsung Galaxy S Duos S7562' => 'compatible_device:Samsung Galaxy S Duos',
        'Samsung Galaxy Xcover 3 SM-G388F' => 'compatible_device:Samsung Galaxy Xcover 3',
        'Samsung Galaxy S6 Active SM-G890' => 'compatible_device:Samsung Galaxy S6 Active',
        'Samsung Galaxy A8 SM-A800F' => 'compatible_device:Samsung Galaxy A8',
        'Samsung Galaxy J1 SM-J100F' => 'compatible_device:Samsung Galaxy J1',
        'Samsung Galaxy Xcover 2 GT-S7710' => 'compatible_device:Samsung Galaxy Xcover 2',
        'Samsung Galaxy E5 SM-E500F' => 'compatible_device:Samsung Galaxy E5',
        'Samsung Galaxy Grand 3 SM-G7200' => 'compatible_device:Samsung Galaxy Grand 3',
        'Samsung Galaxy A8 SM-A800F' => 'compatible_device:Samsung Galaxy A8',       
        'Samsung Galaxy S5 SM-G900' => 'compatible_device:Samsung Galaxy S5',
        'Samsung Galaxy S3 i9300' => 'compatible_device:Samsung Galaxy S3',
        'Samsung Galaxy Core Prime SM-G360F' => 'compatible_device:Samsung Galaxy Core Prime',
        'Samsung Galaxy S5 Active SM-G870' => 'compatible_device:Samsung Galaxy S5 Active',
        //Kiegészítő típus
        'hátlap' => 'kiegeszito_jellege:Hátlap',
        'tok' => 'kiegeszito_jellege:Tok',
        'képernyővédő fólia' => 'kiegeszito_jellege:Fóliap',
        'töltő- és adatbkábel' => 'kiegeszito_jellege:Töltő és adatkábel',
        'VGA adapter' => 'kiegeszito_jellege',
        'hálózati töltő adapter' => 'kiegeszito_jellege:Hálózati töltő',
        'sztereó headset mikrofonnal' => 'kiegeszito_jellege:Headset',
        'Gyári akkumulátor' => 'kiegeszito_jellege:Gyári akkumulátor',
        'telefontartó' => 'kiegeszito_jellege:Telefontartó',
        'kihangosító' => 'kiegeszito_jellege:Kihangosító',
        'Huawei' => 'new_new_cat_brand',
        'Nokia' => 'new_cat_brand',
        'Samsung' => 'new_cat_brand',
        'Sony Ericsson' => 'new_cat_brand',
        'Sony' => 'new_cat_brand',
        'Apple' => 'new_cat_brand',
        'LG' => 'new_cat_brand',
        'Acer' => 'new_cat_brand',
        'Alcatel' => 'new_cat_brand',
        'Apple' => 'new_cat_brand',
        'ASUS' => 'new_cat_brand',
        'BlackBerry' => 'new_cat_brand',
        'HTC' => 'new_cat_brand',
        'Huawei' => 'new_cat_brand',
        'Lenovo' => 'new_cat_brand',
        'LG' => 'new_cat_brand',
        'Microsoft' => 'new_cat_brand',
        'Motorola' => 'new_cat_brand',
        'Telenor' => 'new_cat_brand',
        'Vodafone' => 'new_cat_brand',
        'Wayteq' => 'new_cat_brand',
        'ZTE' => 'new_cat_brand',
        'AKAI' => 'new_cat_brand',
        'Apollo' => 'new_cat_brand',
        'ARCHOS' => 'new_cat_brand',
        'BeeX' => 'new_cat_brand',
        'Blaupunkt' => 'new_cat_brand',
        'Bluepanther' => 'new_cat_brand',
        'Colorovo' => 'new_cat_brand',
        'ConCorde' => 'new_cat_brand',
        'DPS' => 'new_cat_brand',
        'Fujitsu' => 'new_cat_brand',
        'Funscreen' => 'new_cat_brand',
        'GoClever' => 'new_cat_brand',
        'HP' => 'new_cat_brand',
        'Kindle' => 'new_cat_brand',
        'KOOBE' => 'new_cat_brand',
        'Maxell' => 'new_cat_brand',
        'Microsoft' => 'new_cat_brand',
        'Modecom' => 'new_cat_brand',
        'MyAudio' => 'new_cat_brand',
        'MyPhone' => 'new_cat_brand',
        'Navon' => 'new_cat_brand',
        'Overmax' => 'new_cat_brand',
        'Pentagram' => 'new_cat_brand',
        'Point of View' => 'new_cat_brand',
        'Prestigio' => 'new_cat_brand',
        'Toshiba' => 'new_cat_brand',
        'Xiaomi' => 'new_cat_brand',
        'SAMSUNG' => 'new_cat_brand',
        'NOKIA' => 'new_cat_brand',
        'SAGEM' => 'new_cat_brand',
        'Sagem' => 'new_cat_brand',
        'Univerzális' => 'new_cat_brand',
        'Asus' => 'new_cat_brand',
        'Ericsson' => 'new_cat_brand',
        'Evolve' => 'new_cat_brand',
        'Evolveo' => 'new_cat_brand',
        'Univerzális' => 'new_cat_brand'
    );

    $megtalaltak = array();
    foreach ($tulajdonsag_lista as $tulajdonsag => $oszlop) {
        if (strpos($tulajdonsagok, $tulajdonsag) !== false && !isset($megtalaltak[$oszlop])) {
            $oszlopchunk = explode(':', $oszlop);
            if (count($oszlopchunk) > 1) {
                $megtalaltak[$oszlopchunk[0]] = $oszlopchunk[1];
            } else {
                $megtalaltak[$oszlop] = $tulajdonsag;
            }
        }
    }

    return $megtalaltak;
}
