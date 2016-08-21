<?php

namespace rex;

require_once 'RexRouter.php';

class RexBuilder {

    public static function collector($array) {
        $router = new RexRouter();
        foreach ($array as $key => $val) {
        	$split = explode('|', $key);
            $router->setInterfaces($split[0], $split[1], $val);
        }
        $router->run();
    }
}