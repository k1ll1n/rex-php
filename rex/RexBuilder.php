<?php

namespace rex;

use Exception;
use rex\utils\RexException;

require_once 'RexRouter.php';

class RexBuilder {

    /**
     * @param $array - item example - 'HTTP_METHOD|/PATH' => YourClass::class
     * @throws Exception
     */
    public static function collector($array = null) {
        if ($array == null) RexException::showException('You must pass an array!');
        $router = new RexRouter();

        foreach ($array as $key => $val) {
            list($method, $url) = explode(':', $key, 2);
            $router->setInterfaces($method, $url, $val);
        }
        $router->run();
    }
}