<?php

namespace AndyTruong\Bible;

use AndyTruong\App\Application as BaseApplication;

class Application extends BaseApplication
{

    public function __construct()
    {
        parent::__construct(dirname(__DIR__));
    }

}
