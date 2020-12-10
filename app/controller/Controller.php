<?php

namespace controller;

use framework\Tpl;

class Controller extends Tpl
{
    function __construct()
    {
        $config = $GLOBALS['config'];
        parent::__construct($config['TPL_VIEW'], $config['TPL_CACHE']);
    }

    function display($viewName, $isInclude = true, $uri = null)
    {
        if (empty($viewName)){
            $viewName = $_GET['c'].'/'.$_GET['a'].'.html';
        }
        parent::display($viewName, $isInclude, $uri);
    }
}
