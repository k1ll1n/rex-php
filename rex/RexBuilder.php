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
	        if (!strpos($key, '|') !== false) RexException::showException('You must specify the http method and divide it by a vertical bar on the URL path!');
        	$split = explode('|', $key);

            $router->setInterfaces($split[0], $split[1], $val);
        }
        $router->run();
    }
}