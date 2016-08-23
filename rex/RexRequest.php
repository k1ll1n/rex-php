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

	/**
	 * @param $name
	 * @return mixed
	 */
	public function getParam($name = '') {
		if ($name == '' && count($this->params) != 0) {
			return $this->getFirstElement($this->params);
		}
        return $this->params[$name];
    }

    /**
     * @return mixed
     */
    public function getQueryArray() {
        return $this->query;
    }

	/**
	 * @param $name
	 * @return mixed
	 */
	public function getQuery($name = '') {
		if ($name == '' && count($this->query) != 0) {
			return $this->getFirstElement($this->query);
		}
        return $this->query[$name];
    }

    /**
     * @return mixed
     */
    public function getDataArray() {
        return $this->data;
    }

	/**
	 * @param $name
	 * @return mixed
	 */
	public function getData($name = '') {
		if ($name == '' && count($this->data) != 0) {
			return $this->getFirstElement($this->data);
		}
        return $this->data[$name];
    }

	/**
	 * @param $array
	 * @return null
	 */
	private function getFirstElement($array) {
	    foreach ($array as $key => $val) {
		    return $val;
	    }

	    return null;
    }
}