<?php
/**
 * Created by PhpStorm.
 * User: madlax
 * Date: 12.06.16
 * Time: 0:57
 */

namespace simplerest\builder\model;

use simplerest\route\Route;

class BuildModel {
    
    private $interfaces;
    private $handler;
    private $method;

    /**
     * Model constructor.
     * @param $method
     * @param $interfaces
     * @param $route
     */
    public function __construct($method, $interfaces, Route $route) {
        $this->method = $method;
        $this->interfaces = $interfaces;
        $this->handler = $route;
    }

    /**
     * @return mixed
     */
    public function getInterfaces() {
        return $this->interfaces;
    }

    /**
     * @return mixed
     */
    public function getHandler() {
        return $this->handler;
    }

    /**
     * @return mixed
     */
    public function getMethod() {
        return $this->method;
    }
}