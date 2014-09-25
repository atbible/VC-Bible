<?php

namespace AndyTruong\Bible;

use AndyTruong\App\Application as BaseApplication;

class Application extends BaseApplication
{

    public function __construct($appDir = null)
    {
        parent::__construct(null === $appDir ? dirname(__DIR__) : $appDir);
    }

}
