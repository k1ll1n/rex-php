<?php

namespace rex;

class RexRequest {

	private $params;
	private $query;
	private $data;
	private $body;

	/**
	 * RexRequest constructor.
	 * @param $params
	 * @param $query
	 * @param $data
	 * @param $body
	 * @internal param $headers
	 * @internal param $contentType
	 */
	public function __construct($params, $query, $data, $body) {
		$this->params = $params;
		$this->query = $query;
		$this->data = $data;
		$this->body = $body;
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function params($name = '') {
		if ($name != '') {
			return $this->params[$name];
		}
		return $this->params;
    }

	/**
	 * @param $name
	 * @return mixed
	 */
	public function queryParams($name = '') {
		if ($name != '') {
			return $this->query[$name];
		}
		return $this->query;
    }

	/**
	 * @param $name
	 * @return mixed
	 */
	public function data($name = '') {
		if ($name != '') {
			return $this->data[$name];
		}

		return $this->data;
    }

	/**
	 * @return mixed
	 */
	public function body() {
		return $this->body;
	}

	/**
	 * @return mixed
	 */
	public function contentType() {
		return $_SERVER['CONTENT_TYPE'];
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function headers($name = '') {

		if ($name != '') {
			return getallheaders()[$name];
		}
		$headers = [];
		foreach (getallheaders() as $key => $val) {
			array_push($headers, $key);
		}

		return $headers;
	}
}