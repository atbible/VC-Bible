<?php

use AndyTruong\Bible\Application;

@include_once dirname(__DIR__) . '/vendor/autoload.php';
@include_once dirname(__DIR__) . '/../../autoload.php';

$app = new Application;
if (isset($_SERVER['REQUEST_URI'])) { // Handle default all web requests
    $app->getRestler()->handle();
}
else { // Start console
    $app->console();
}
