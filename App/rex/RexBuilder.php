<?php

require_once 'Router.php';

class RexBuilder {

    public static function collector($array) {
        $router = new Router();
        foreach ($array as $key => $val) {
            $router->setInterfaces($val->getMethod(), $val->getInterfaces(), $val->getHandler());
        }
        $router->init();
    }
}