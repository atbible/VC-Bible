<?php

namespace AndyTruong\Bible;

use AndyTruong\App\Application as BaseApplication;
use AndyTruong\Bible\Helper\AccessControl;
use Luracast\Restler\Scope;

class Application extends BaseApplication
{

    protected $name = 'Bible Reading application';
    protected $version = '0.1.0-dev';

    public function __construct($appDir = null)
    {
        parent::__construct(null === $appDir ? dirname(__DIR__) : $appDir);
    }

    public function getRestler()
    {
        $init = !$this->hasRestler();
        $restler = parent::getRestler();

        if ($init) {
            $restler->addAuthenticationClass('AndyTruong\Bible\Helper\AccessControl');
            Scope::register('AndyTruong\Bible\Helper\AccessControl', function() {
                return new AccessControl($this);
            });
        }

        return $restler;
    }

}
