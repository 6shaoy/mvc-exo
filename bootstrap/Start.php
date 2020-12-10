<?php

class Start
{
    public static $auto;

    public static function init()
    {
        self::$auto = new Psr4Autoload();
    }

    public static function router()
    {
        $c = $_GET['c'] ?? 'index';
        $a = $_GET['a'] ?? 'index';

        $c = ucfirst(strtolower($c));

        $controller = 'controller\\' . $c . 'Controller';

        $obj = new $controller();
        call_user_func([$obj, $a]);
    }
}
