<?php
global $configApp;
global $db;
$configApp = parse_ini_file("/../secrets.ini");
$db = array(
    'defaults' => array(
        'host' => '127.0.0.1',
        'port' => '3308', //default 3306
        'pw' => $configApp['db.password'], // make changes in secrets.ini
        'un' => $configApp['db.user'], // make changes in secrets.ini
        'db' => 'ankieta',
        ),
    );

