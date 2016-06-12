<?php
namespace rex\router;

use rex\request\Request;
use rex\route\Route;

class Router {
    private $routes = array();
    private $method = '';

    function get($method, $pattern, Route $route) {
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
        
        foreach ($this->routes[$this->method] as $map) {
            if (preg_match($this->makePattern($map['pattern']), $url_path, $matches)) {
                $this->setParams(
                    $map['class'], 
                    $this->getVariables($map['pattern']), 
                    $this->getValues($url_path, $map['pattern'])
                );
                
                break;
            }
        }
    }

    private function setParams(Route $route, $variables, $values) {
        $request = new Request();
        $params = array();
        foreach ($variables as $key => $val) {
            $params[$val] = $values[$key];
        }
        $request->setParams($params);
        
        $request->setQuery($this->setRequestQuery());

        switch ($this->method) {
            case 'POST': {
                $request->setData($_POST);
                break;
            }
            default:
                $request->setData($this->setRequestData());
        }

        $route->handle($request);
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
        
        return 0;
    }
    
    private function getValues($url, $pattern) {
        $pattern = preg_replace('#\:\w{1,}#', '(\d{1,})', $pattern);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '#'.$pattern.'#';
        if (preg_match($pattern, $url, $matches)) {
            array_shift($matches);
            return $matches;
        }
        
        return 0;
    }
}