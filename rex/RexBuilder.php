<?php

namespace rex;

use Exception;
use rex\utils\RexError;

require_once 'RexRouter.php';

class RexBuilder {

    /**
     * @param $array - item example - 'HTTP_METHOD|/PATH' => YourClass::class
     * @throws Exception
     */
    public static function collector($array = null) {
        if ($array == null) RexError::showError(['message' => 'You must pass an array!']);

        $router = new RexRouter();

        foreach ($array as $key => $val) {
            list($method, $url) = explode(':', $key, 2);

            if(!self::checkMethod($method)) {
                RexError::showError([
                    'message' => 'This \'' . $method . '\' http method is not supported'
                ]);
                return;
            }

            $router->setInterfaces($method, $url, $val);
        }
        $router->run();
    }

    private static function checkMethod($method) {
        switch ($method) {
            case 'GET': return true;
            case 'POST': return true;
            case 'PUT': return true;
            case 'PATCH': return true;
            case 'DELETE': return true;
            case 'OPTIONS': return true;
            default: return false;
        }
    }
}