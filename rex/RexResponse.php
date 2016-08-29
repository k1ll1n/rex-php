<?php

namespace rex;


class RexResponse {
	private $session = false;
	private $sessionArray = [];
	private $unsetSessionVar = [];
	private $failUnsetSessionVar = [];
	private $destroyCurrentSession = false;

	/**
	 * @param $object
	 * @param bool $debug
	 */
	public function show($object, $debug = false) {

		$this->sessionDestroy();
		$this->pushDataToSession();
		$this->deleteDataFromSession();

		if (!$debug) {
			echo $object;
		} else {
			echo '<pre>';
			var_dump($object);
			echo '</pre>';
		}
	}

	/**
	 * @param $name
	 * @param $value
	 */
	public function setSession($name, $value) {
		$this->session = true;
		array_push($this->sessionArray, [$name => $value]);
	}

	/**
	 * @param $name
	 */
	public function unsetSession($name) {
		array_push($this->unsetSessionVar, $name);
	}

	public function destroySession() {
		$this->destroyCurrentSession = true;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getSession($name = '') {
		if (session_status() == 0 or session_status() == 1){session_start();};
		if ($name != '') {
			return $_SESSION[$name];
		}

		return $_SESSION;
	}

	private function sessionDestroy() {
		if ($this->destroyCurrentSession) {
			if (session_status() == 0 or session_status() == 1){session_start();};
			$_SESSION = array();
			session_destroy();
		}
	}

	private function pushDataToSession() {
		if ($this->session and count($this->sessionArray) > 0) {
			if (session_status() == 0 or session_status() == 1){session_start();};
			foreach ($this->sessionArray as $item) {
				foreach ($item as $key => $value) {
					$_SESSION[$key] = $value;
				}
			}
		}
	}

	private function deleteDataFromSession() {
		if (count($this->unsetSessionVar) > 0) {
			if (session_status() == 0 or session_status() == 1){session_start();};
			foreach ($this->unsetSessionVar as $item) {
				if (array_key_exists($item, $_SESSION)) {
					unset($_SESSION[$item]);
				} else {
					array_push($this->failUnsetSessionVar, $item);
				}
			}
		}

		if (count($this->failUnsetSessionVar) > 0) {
			exit(json_encode(array(
				'error' => 2,
				'message' => 'Следующие переменные не обнаруженые в данной сессии',
				'variables' => $this->failUnsetSessionVar
			)));
		}
	}
}