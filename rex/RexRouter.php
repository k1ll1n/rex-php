<?php

namespace rex;

use Exception;
use rex\utils\RexError;
use rex\utils\Server;

require_once 'utils/RexClassLoader.php';


class RexRouter {
    private $routes = [];

    /**
     * @param $method - http method
     * @param $pattern - special format url path
     * @param $handler - YourClass::class
     */
    function setInterfaces($method, $pattern, $handler) {
        $this->makeArray($method);
        array_push($this->routes[$method],
            ['pattern' => $pattern,
                'class' => $handler]
        );
    }

    /**
     * @param $method
     */
    private function makeArray($method) {
        if (!array_key_exists($method, $this->routes)) {
            $this->routes[$method] = [];
        }
    }

    /**
     * @param $pattern
     * @return string
     *
     * Conversion URL path to regular expression
     */
    private function makePattern($pattern) {
        return '#' . str_replace('/', '\/', preg_replace('#\:\w{1,}#', '[A-z0-9]+', $pattern)) . '($|\?.*)#';
    }

    function run() {
        $url_path = parse_url(Server::requestUri(), PHP_URL_PATH);

        /*if (!array_key_exists(Server::requestMethod(), $this->routes)) { //TODO Протестировать на необходимость данной проверки.
            RexError::showError([
                'error' => 1,
                'message' => 'Не обнаружен интерфейс с таким методом!',
                'interface' => $url_path,
                'http method' => Server::requestMethod()
            ]);
        }*/

        foreach ($this->routes[Server::requestMethod()] as $map) {
            if (preg_match($this->makePattern($map['pattern']), $url_path, $matches)) {
                $this->setParams(
                    new $map['class'](),
                    $this->getVariables($map['pattern']),
                    $this->getValues($url_path, $map['pattern'])
                );

                break;
            } else {
                RexError::showError([
                    'message' => 'Данный URL Path не удалось сопоставить ни с одним зарегистрированным интерфейсом!',
                    'interface' => $url_path,
                    'http method' => Server::requestMethod()
                ]);
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
        if (count($variables) > 0 && count($values) > 0) {
            foreach ($variables as $key => $val) {
                $params[$val] = $values[$key];
            }
        }

        $data = null;
        switch (Server::requestMethod()) {
            case 'POST': {
                $data = $_POST;
                break;
            }
            default:
                $data = '';
        }

        $request = new RexRequest(
            $params,
            $this->setRequestQuery(),
            $data,
            $this->setRequestBody()
        );
        $route->handle(new RexResponse(), $request);
    }

    /**
     * @return array
     */
    private function setRequestQuery() {
        if (Server::queryString() == '') return [];

        $dataArray = explode('&', Server::queryString());
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
        $template = '#' . str_replace('/', '\/', preg_replace('#\:\w{1,}#', '(\:\w{1,})', $pattern)) . '#';
        if (preg_match($template, $pattern, $matches)) {
            array_shift($matches);
            return str_replace(':', '', $matches);
        }

        return [];
    }

    /**
     * @param $url
     * @param $pattern
     * @return mixed
     * @throws Exception
     */
    private function getValues($url, $pattern) {
        $pattern = '#' . str_replace('/', '\/', preg_replace('#\:\w{1,}#', '(\d{1,})', $pattern)) . '#';
        if (preg_match($pattern, $url, $matches)) {
            array_shift($matches);
            return $matches;
        }

        return [];
    }
}