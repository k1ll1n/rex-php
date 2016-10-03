<?php

namespace rex;

use ReflectionClass;
use rex\models\RexCookie;
use rex\utils\RexError;


class RexResponse {
    private $session = false;
    private $sessionArray = [];
    private $unsetSessionVar = [];
    private $failUnsetSessionVar = [];
    private $destroyCurrentSession = false;
    private $cookies = [];

    /**
     * @param $object
     * @param bool $debug
     */
    public function show($object, $debug = false) {

        $this->sessionDestroy();
        $this->pushDataToSession();
        $this->deleteDataFromSession();
        $this->setCookie();

        if (is_array($object)) {
            echo 'To display all the content except for a string, use the method "show" with debug option!';
            return;
        }

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
        if (session_status() == 0 or session_status() == 1) {
            session_start();
        };
        if ($name != '' && count($_SESSION) > 0) {
            return $_SESSION[$name];
        }

        return $_SESSION;
    }

    public function cookie(RexCookie $cookie) {
        array_push($this->cookies, $cookie);
    }

    public function destroyCookie($name = '') {
        if ($name != '') {
            setcookie($name, '', -1);
            return;
        }

        if (count($_COOKIE) > 0) {
            foreach ($_COOKIE as $key => $value) {
                setcookie($key, '', -1);
            }
        }
    }

    private function sessionDestroy() {
        if ($this->destroyCurrentSession) {
            if (session_status() == 0 or session_status() == 1) {
                session_start();
            };
            $_SESSION = array();
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', -1,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
        }
    }

    private function pushDataToSession() {
        if ($this->session and count($this->sessionArray) > 0) {
            if (session_status() == 0 or session_status() == 1) {
                session_start();
            };
            foreach ($this->sessionArray as $item) {
                foreach ($item as $key => $value) {
                    $_SESSION[$key] = $value;
                }
            }
        }
    }

    private function deleteDataFromSession() {
        if (count($this->unsetSessionVar) > 0) {
            if (session_status() == 0 or session_status() == 1) {
                session_start();
            };
            foreach ($this->unsetSessionVar as $item) {
                if (array_key_exists($item, $_SESSION)) {
                    unset($_SESSION[$item]);
                } else {
                    array_push($this->failUnsetSessionVar, $item);
                }
            }
        }

        if (count($this->failUnsetSessionVar) > 0) {
            RexError::showError([
                'error' => 2,
                'message' => 'Следующие переменные не обнаружены в данной сессии',
                'variables' => $this->failUnsetSessionVar
            ]);
        }
    }

    private function setCookie() {
        if (count($this->cookies) > 0) {
            foreach ($this->cookies as $item) {
                setcookie(
                    $this->getCookieProp($item, 'name'),
                    $this->getCookieProp($item, 'value'),
                    $this->getCookieProp($item, 'expiryTime'),
                    $this->getCookieProp($item, 'path'),
                    $this->getCookieProp($item, 'domain'),
                    $this->getCookieProp($item, 'secure'),
                    $this->getCookieProp($item, 'httpOnly')
                );
            }
        }
    }

    private function getCookieProp($obj, $name) {
        $reflect = new ReflectionClass($obj);
        $prop = $reflect->getProperty($name);
        $prop->setAccessible(true);
        return $prop->getValue($obj);
    }
}