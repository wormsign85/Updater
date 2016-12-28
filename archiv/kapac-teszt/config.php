<?php

$config = array(
    'system' => array(
        'axapta_xml_dir' => '/home/-www/wormsign.hu/htdocs/kereso/update/kapac-teszt',
        'log_dir' => '/home/-www/wormsign.hu/htdocs/kereso/update/log'
    ),
    'unas_soap' => array(
        'mitsubishi' => array(
            'Username' => 'Wormsign',
            'PasswordCrypt' => '8851c79988a095a9b529d8b92f96aeb1',
            'ShopId' => '50410',
            'AuthCode' => 'a6bdd62a0c'
        ),
        'akku' => array(
            'Username' => 'Wormsign',
            'PasswordCrypt' => '8851c79988a095a9b529d8b92f96aeb1',
            'ShopId' => '39862',
            'AuthCode' => 'e08564a155'
        )));

$config_db_sku = array (
    'username' =>'wormsignh_worm',
    'password' =>'IxOn1985',
    'connection' =>'mysql:host=localhost;dbname=wormsignh_kapacitas_sku',
);
