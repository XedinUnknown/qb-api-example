<?php

require_once 'bootstrap.php';

$handler = $c->get('auth_handler');

$handler->run();

