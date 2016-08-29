<?php
/**
 * Created by PhpStorm.
 * User: madlax
 * Date: 29.08.16
 * Time: 15:27
 */

namespace rex\utils;


class Server {

	public static function requestUri() {
		return $_SERVER['REQUEST_URI'];
	}

	public static function requestMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}

	public static function queryString() {
		return $_SERVER['QUERY_STRING'];
	}
}