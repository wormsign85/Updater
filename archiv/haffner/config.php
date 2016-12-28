<?php

$rootDir = realpath(dirname((__FILE__))) . '/';

$config = array(
    'system' => array(
        'axapta_xml_dir' => $rootDir,
        'log_dir' => $rootDir . '/log/',
    ),
    'unas_soap' => array(
        'akku' => array(
            'Username' => 'Wormsign',
            'PasswordCrypt' => '8851c79988a095a9b529d8b92f96aeb1',
            'ShopId' => '39862',
            'AuthCode' => 'e08564a155'
        )/*,
        'mitsu' => array(
            'Username' => 'Wormsign',
            'PasswordCrypt' => '8851c79988a095a9b529d8b92f96aeb1',
            'ShopId' => '50410',
            'AuthCode' => 'a6bdd62a0c'
        )*/
       ));


$curl_user_pass = array(
    'url' => 'http://haffner24.hu/prod_short.php?id=522&pass=b9d3f1256139bb019736e0025d1dcd0d',
    'user_pass' => 'unas:g3r0n1m0'
);

$config_db = array(
    'username' => 'wormsignh_worm',
    'password' => 'IxOn1985',
    'connection' => 'mysql:host=localhost;dbname=wormsignh_wormsign_hu',
);