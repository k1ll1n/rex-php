<?php

namespace rex;

use Exception;
use rex\utils\RexException;


class RexRouter {
    private $routes = [];
    private $method = '';

	/**
	 * @param $method - http method
	 * @param $pattern - special format url path
	 * @param $handler - YourClass::class
	 */
	function setInterfaces($method, $pattern, $handler) {
        $this->method = $method;
        $this->makeArray();
        array_push($this->routes[$method],
	        ['pattern'   => $pattern,
            'class'     => $handler]
        );
    }

	/**
	 *
	 */
	private function makeArray() {
        if (!array_key_exists($this->method, $this->routes)) {
            $this->routes[$this->method] = [];
        }
    }

	/**
	 * @param $pattern
	 * @return string
	 *
	 * Conversion URL path to regular expression
	 */
	private function makePattern($pattern) {
        return '#'.str_replace('/', '\/', preg_replace('#\:\w{1,}#', '[A-z0-9]+', $pattern)).'($|\?.*)#';
    }

	function run() {
        $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $map) {
            if (preg_match($this->makePattern($map['pattern']), $url_path, $matches)) {
                $this->setParams(
                    new $map['class'](),
                    $this->getVariables($map['pattern']),
                    $this->getValues($url_path, $map['pattern'])
                );

                break;
            } else {

	            echo json_encode(array(
	            	'error' => 1,
		            'message' => 'Данный интерфейс не найден!',
		            'interface' => $url_path
	            ));
	            return;
            }
        }
    }

	/**
	 * @param RexHandlerInterface $route
	 * @param $variables
	 * @param $values
	 */
	private function setParams(RexHandlerInterface $route, $variables, $values) {
        $params = [];
        foreach ($variables as $key => $val) {
            $params[$val] = $values[$key];
        }
        $data = null;
        switch ($this->method) {
            case 'POST': {
                $data = $_POST;
                break;
            }
            default:
                $data = $this->setRequestData();
        }

        $request = new RexRequest($params,
	        $this->setRequestQuery(),
	        $data,
	        $this->setRequestBody());
        $route->handle($request);
    }

	/**
	 * @return array
	 */
	private function setRequestQuery() {
        if ($_SERVER['QUERY_STRING'] == '') return [];

        $dataArray = explode('&', $_SERVER['QUERY_STRING']);
        $data = [];
        foreach ($dataArray as $key => $val) {
            $explode = explode('=', $val);
            $data[$explode[0]] = $explode[1];
        }
        return $data;
    }

	/**
	 * @return array
	 *
	 * Processing other http methods, which do not have their own global variables such as PUT, DELETE, etc.
	 */
	private function setRequestData() {
        if (file_get_contents('php://input') == '') return [];

        $dataArray = explode('&', file_get_contents('php://input'));
        $data = [];
        foreach ($dataArray as $key => $val) {
            $explode = explode('=', $val);
            $data[$explode[0]] = $explode[1];
        }
        return $data;
    }

	/**
	 * @return string
	 */
	private function setRequestBody() {
	    if (file_get_contents('php://input') == '') return '';
	    return file_get_contents('php://input');
    }

	/**
	 * @param $pattern
	 * @return mixed
	 * @throws Exception
	 */
	private function getVariables($pattern) {
        $template = preg_replace('#\:\w{1,}#', '(\:\w{1,})', $pattern);
        $template = str_replace('/', '\/', $template);
        $template = '#'.$template.'#';
        if (preg_match($template, $pattern, $matches)) {
            array_shift($matches);
            return str_replace(':', '', $matches);
        }
        RexException::showException('Unable to get variables from a pattern!');
    }

	/**
	 * @param $url
	 * @param $pattern
	 * @return mixed
	 * @throws Exception
	 */
	private function getValues($url, $pattern) {
        $pattern = preg_replace('#\:\w{1,}#', '(\d{1,})', $pattern);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '#'.$pattern.'#';
        if (preg_match($pattern, $url, $matches)) {
            array_shift($matches);
            return $matches;
        }

        RexException::showException('Unable to get data from the url!');
    }
}