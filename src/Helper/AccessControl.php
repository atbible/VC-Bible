<?php

namespace AndyTruong\Bible\Helper;

use AndyTruong\Bible\Application;
use Luracast\Restler\iAuthenticate;

class AccessControl implements iAuthenticate
{

    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function __getWWWAuthenticateString()
    {
        return 'Basic realm="My Realm"';
    }

    public function __isAllowed()
    {
        header('WWW-Authenticate: Basic realm="Admin"');
        header('HTTP/1.0 401 Unauthorized');
        $config = $this->app->configGet('restler.auth.basic');
        $matchUser = isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] === $config['user'];
        $matchPass = isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_PW'] === $config['password'];
        return $matchUser && $matchPass;
    }

}
