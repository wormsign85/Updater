<?php

function szetbontTulajdonsagok($tulajdonsagok) {
    $tulajdonsag_lista = array(
        //színek
        'fehér' => 'szin',
        'világos kék' => 'szin',
        'kék' => 'szin',
        'fekete' => 'szin',
        'blue' => 'szin',
        'green' => 'szin',
        'pink' => 'szin',
        'turquoise' => 'szin',
        'yellow' => 'szin',
        'black' => 'szin',
        'brown' => 'szin',
        'white' => 'szin',
        'pink' => 'szin',
        'orange' => 'szin',
        'átlátszó' => 'szin',
        'transparent' => 'szin',
        'aqua sky' => 'szin',
        'glacier' => 'szin',
        'melon pop' => 'szin',
        'neon rose' => 'szin',
        'ocean' => 'szin',
        'black/silver' => 'szin',
        'grey/black' => 'szin',
        'grey' => 'szin',
        'silver/black' => 'szin',
        'white/classic' => 'szin',
        'sárga' => 'szin',
        'piros' => 'szin',
        'brigth red' => 'szin',
        'red' => 'szin',
        //kiszerelés univerzális
        'univerzális' => 'kiszereles',
        //méretezés
        'Slim' => 'meret',
        //vízállóság
        'vízálló' => 'vizallosag',
        //Védelem
        'víz- por- és ütésálló' => 'vedelem',
        //Kivitel
        'flipes' => 'kivitel',
        'csatos-fűzős' => 'kivitel',
        //Anyag
        'valódi bőrtok' => 'anyag',
        'bőrtok' => 'anyag',
        'szilikon' => 'anyag',
        //gyári vagy nem
        'gyári' => 'eredetiseg',
        //Fólia típusa, képernyővédő vagy hátlapvédő stb.
        'képernyővédő fólia' => 'folia_tipus',
        'hátlapvédő fólia' => 'folia_tipus',
        'gyémántüveg' => 'folia_tipus',
        //átlátszóság
        'Clear' => 'lathatosag',
        'Anti-Glare' => 'lathatosag',
        'Anti-Finger' => 'lathatosag',
        'Frosted' => 'lathatosag',
        'Privacy' => 'lathatosag',
        'PRIVACY' => 'lathatosag',
        'AntiCrash Crystal' => 'lathatosag',
        'Ultra Clear' => 'lathatosag',
        'Newlook/Crystal' => 'lathatosag',
        'Crystal/Antireflex HD' => 'lathatosag',
        'Glossy/Matt' => 'lathatosag',
        'AntiCrash Crystal' => 'lathatosag',
        //darab/csomag
        '3 db/csomag' => 'csomagolas',
        '2 db/csomag' => 'csomagolas',
        '1 db/csomag' => 'csomagolas',
        '1+3 db/csomag' => 'csomagolas',
        '2+2+2 db/csomag' => 'csomagolas',
        //hátlapok
        'ütésálló' => 'vedelem',
        'védőtok' => 'folia_tipus'
    );

    $megtalaltak = array();
    foreach ($tulajdonsag_lista as $tulajdonsag => $oszlop) {
        if (strpos($tulajdonsagok, $tulajdonsag) !== false && !isset($megtalaltak[$oszlop])) {
            $megtalaltak[$oszlop] = $tulajdonsag;
        }
    }

    return $megtalaltak;
}
