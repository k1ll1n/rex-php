<?php

class Router {
    private $routes = array();
    private $method = '';

    function setInterfaces($method, $pattern, Route $route) {
        $this->method = $method;
        $this->makeArray();
        array_push($this->routes[$method], array(
            'pattern'   => $pattern,
            'class'     => $route
        ));
    }

    private function makeArray() {
        if (!array_key_exists($this->method, $this->routes)) {
            $this->routes[$this->method] = array();
        }
    }

    private function makePattern($pattern) {
        $p = '#\:\w{1,}#';
        $pattern = preg_replace($p, '(\d{1,})', $pattern);
        $pattern = str_replace('/', '\/', $pattern);
        return '#'.$pattern.'#';
    }
    
    function init() {
        $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $map) {
            if (preg_match($this->makePattern($map['pattern']), $url_path, $matches)) {
                $this->setParams(
                    $map['class'],
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

    private function setParams(Route $route, $variables, $values) {
        $params = array();
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

        $route->handle(new Request($params, $this->setRequestQuery(), $data));
    }

    private function setRequestQuery() {
        if ($_SERVER['QUERY_STRING'] == '') return array();

        $dataArray = explode('&', $_SERVER['QUERY_STRING']);
        $data = array();
        foreach ($dataArray as $key => $val) {
            $explode = explode('=', $val);
            $data[$explode[0]] = $explode[1];
        }
        return $data;
    }

    private function setRequestData() {
        if (file_get_contents('php://input') == '') return array();

        $dataArray = explode('&', file_get_contents('php://input'));
        $data = array();
        foreach ($dataArray as $key => $val) {
            $explode = explode('=', $val);
            $data[$explode[0]] = $explode[1];
        }
        return $data;
    }

    private function getVariables($pattern) {
        $template = preg_replace('#\:\w{1,}#', '(\:\w{1,})', $pattern);
        $template = str_replace('/', '\/', $template);
        $template = '#'.$template.'#';
        if (preg_match($template, $pattern, $matches)) {
            array_shift($matches);
            return str_replace(':', '', $matches);
        }
        
        throw new Exception('Unable to get variables from a pattern!');
    }
    
    private function getValues($url, $pattern) {
        $pattern = preg_replace('#\:\w{1,}#', '(\d{1,})', $pattern);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '#'.$pattern.'#';
        if (preg_match($pattern, $url, $matches)) {
            array_shift($matches);
            return $matches;
        }

        throw new Exception('Unable to get data from the url!');
    }
}