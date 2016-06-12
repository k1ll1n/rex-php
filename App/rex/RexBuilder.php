<?php

namespace rex\builder;

require 'Router.php';

use rex\router\Router;

class RexBuilder {
    static function collector($array) {
        $router = new Router();
        foreach ($array as $key => $val) {
            $router->get($val->getMethod(), $val->getInterfaces(), $val->getHandler());
        }
        
        $router->init();
    }
}