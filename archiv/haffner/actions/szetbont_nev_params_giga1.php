<?php

ata_mysql_query("set names 'utf8'", $connection);

function szetbontTulajdonsagok($tulajdonsagok) {
    $tulajdonsag_lista = array(
        //színek
    /*    'fehér' => 'szin',
        'világos kék' => 'szin',
        'kék' => 'szin',
        'fekete' => 'szin',
        'blue' => 'szin',
        'green' => 'szin',
        'pink' => 'szin',
        'turquoise' => 'szin',
        'yellow' => 'szin:sárga',
        'black' => 'szin:fekete',
        'brown' => 'szin:barna',
        'white' => 'szin:fehér',
        'pink' => 'szin:rózsaszín',
        'orange' => 'szin:narancs',
        'átlátszó' => 'szin',
        'transparent' => 'szin:átlátszó',
        'aqua sky' => 'szin:égszínkék',
        'glacier' => 'szin',
        'melon pop' => 'szin',
        'neon rose' => 'szin:rózsa',
        'ocean' => 'szin:óceán',
        'black/silver' => 'szin:fekete/ezüst',
        'grey/black' => 'szin:szürke/fekete',
        'grey' => 'szin:szürke',
        'silver/black' => 'szin:ezüst/fekete',
        'white/classic' => 'szin:fehér/klasszikus',
        'sárga' => 'szin',
        'piros' => 'szin',
        'brigth red' => 'szin:világos piros',
        'red' => 'szin:piros',
        'lila' => 'szin',
        'black/green' => 'szin:fekete/zöld',
        'black/red' => 'szin:fekete/piros',
        //kiszerelés univerzális
        'univerzális' => 'kiszereles',
        //méretezés
        'Slim' => 'meret',
        //vízállóság
        'vízálló' => 'vizallosag',
        //Védelem
        'víz- por- és ütésálló' => 'vedelem',
        //Kivitel
        'csatos-fűzős' => 'kivitel',
        //Anyag
        'valódi bőrtok' => 'anyag',
        'bőrtok' => 'anyag',
        'szilikon' => 'anyag',
        //gyári vagy nem
        'gyári' => 'eredetiseg',
        //Fólia típusa, képernyővédő vagy hátlapvédő stb.
//        'képernyővédő fólia' => 'folia_tipus',
//        'hátlapvédő fólia' => 'folia_tipus',
//        'gyémántüveg' => 'folia_tipus',
        //átlátszóság
        'Clear' => 'lathatosag',
        'Anti-Glare' => 'lathatosag',
        'Anti-Finger' => 'lathatosag',
        'Frosted' => 'lathatosag',
        'Privacy' => 'lathatosag',
        'PRIVACY' => 'lathatosag:Privacy',
        'AntiCrash Crystal' => 'lathatosag',
        'Ultra Clear' => 'lathatosag',
        'Newlook/Crystal' => 'lathatosag',
        'Crystal/Antireflex HD' => 'lathatosag',
        'Glossy/Matt' => 'lathatosag',
        'AntiCrash Crystal' => 'lathatosag',
        //darab/csomag
//        '3 db/csomag' => 'csomagolas',
//        '2 db/csomag' => 'csomagolas',
//        '1 db/csomag' => 'csomagolas',
//        '1+3 db/csomag' => 'csomagolas',
//        '2+2+2 db/csomag' => 'csomagolas',
//        //hátlapok
//        'ütésálló' => 'vedelem',
//        'védőtok' => 'folia_tipus',
//        'kartok' => 'folia_tipus',
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
        'kihangosító' => 'kiegeszito_jellege:Kihangosító',  */
        'Huawei' => 'cat_brand',
        'Nokia' => 'cat_brand',
        'Samsung' => 'cat_brand',
        'Sony Ericsson' => 'cat_brand',
        'Sony' => 'cat_brand',
        'Apple' => 'cat_brand',
        'LG' => 'cat_brand',
        'Acer' => 'cat_brand',
        'Alcatel' => 'cat_brand',
        'Apple' => 'cat_brand',
        'ASUS' => 'cat_brand',
        'BlackBerry' => 'cat_brand',
        'HTC' => 'cat_brand',
        'Huawei' => 'cat_brand',
        'Lenovo' => 'cat_brand',
        'LG' => 'cat_brand',
        'Microsoft' => 'cat_brand',
        'Motorola' => 'cat_brand',
        'Telenor' => 'cat_brand',
        'Vodafone' => 'cat_brand',
        'Wayteq' => 'cat_brand',
        'ZTE' => 'cat_brand',
        'AKAI' => 'cat_brand',
        'Apollo' => 'cat_brand',
        'ARCHOS' => 'cat_brand',
        'BeeX' => 'cat_brand',
        'Blaupunkt' => 'cat_brand',
        'Bluepanther' => 'cat_brand',
        'Colorovo' => 'cat_brand',
        'ConCorde' => 'cat_brand',
        'DPS' => 'cat_brand',
        'Fujitsu' => 'cat_brand',
        'Funscreen' => 'cat_brand',
        'GoClever' => 'cat_brand',
        'HP' => 'cat_brand',
        'Kindle' => 'cat_brand',
        'KOOBE' => 'cat_brand',
        'Maxell' => 'cat_brand',
        'Microsoft' => 'cat_brand',
        'Modecom' => 'cat_brand',
        'MyAudio' => 'cat_brand',
        'MyPhone' => 'cat_brand',
        'Navon' => 'cat_brand',
        'Overmax' => 'cat_brand',
        'Pentagram' => 'cat_brand',
        'Point of View' => 'cat_brand',
        'Prestigio' => 'cat_brand',
        'Toshiba' => 'cat_brand',
        'Xiaomi' => 'cat_brand',
        'SAMSUNG' => 'cat_brand',
        'NOKIA' => 'cat_brand',
        'SAGEM' => 'cat_brand',
        'Sagem' => 'cat_brand',
        'Univerzális' => 'cat_brand',
        'Asus' => 'cat_brand'
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