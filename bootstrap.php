<?php

if (file_exists($autoload = 'vendor/autoload.php')) {
    require_once $autoload;
}

$session_id = session_id();
if (empty($session_id))
{
    session_start();
}

$serviceFactory = require_once('services.php');
$services = $serviceFactory(__DIR__);
$c = new \XedinUnknown\QbApiExample\DI_Container($services);
