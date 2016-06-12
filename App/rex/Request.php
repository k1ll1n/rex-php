<?php

namespace rex\request;


class Request {
    private $params;
    private $query;
    private $data;

    /**
     * @return mixed
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params) {
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query) {
        $this->query = $query;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data) {
        $this->data = $data;
    }



}