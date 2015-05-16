<?php

namespace AndyTruong\Bible;

use AndyTruong\App\Application as BaseApplication;

class Application extends BaseApplication
{

    protected $name    = 'Bible Reading application';
    protected $version = '0.1.0-dev';

    public function __construct($appDir = null)
    {
        parent::__construct(null === $appDir ? dirname(__DIR__) : $appDir);
    }

}
