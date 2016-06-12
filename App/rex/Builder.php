<?php


namespace simplerest\builder;

include 'Router.php';

use simplerest\router\Router;

class Builder {
    static function collector($array) {
        $router = new Router();
        foreach ($array as $key => $val) {
            $router->get($val->getMethod(), $val->getInterfaces(), $val->getHandler());
        }
        
        $router->init();
    }
}