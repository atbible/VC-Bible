<?php

use AndyTruong\Bible\Application;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$app = new Application();
isset($_SERVER['REQUEST_URI']) ? $app->getRestler()->handle() : $app->console();
