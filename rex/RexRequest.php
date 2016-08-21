<?php

namespace rex;

class RexRequest {
    private $params;
    private $query;
    private $data;

    /**
     * Request constructor.
     * @param $params
     * @param $query
     * @param $data
     */
    public function __construct($params, $query, $data) {
        $this->params = $params;
        $this->query = $query;
        $this->data = $data;
    }
    
    /**
     * @return mixed
     */
    public function getParamsArray() {
        return $this->params;
    }
    public function getParams($name) {
        return $this->params[$name];
    }

    /**
     * @return mixed
     */
    public function getQueryArray() {
        return $this->query;
    }
    
    public function getQuery($name) {
        return $this->query[$name];
    }
    

    /**
     * @return mixed
     */
    public function getDataArray() {
        return $this->data;
    }

    public function getData($name) {
        return $this->data[$name];
    }
}