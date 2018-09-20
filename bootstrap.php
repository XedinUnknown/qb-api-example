<?php

if (file_exists($autoload = 'vendor/autoload.php')) {
    require_once $autoload;
}

$serviceFactory = require_once('services.php');
$services = $serviceFactory(__DIR__);
