<?php

namespace rex\utils;

use Exception;

class RexException {
	public static function showException ($message) {
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);
		echo '<pre>';
		throw new Exception($message);
		echo '</pre>';
	}
}