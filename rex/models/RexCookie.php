<?php

namespace rex\models;

class RexCookie {
    private $name = '';
    private $value = '';
    private $expiryTime = 0;
    private $path = '';
    private $domain = '';
    private $secure = 0;
    private $httpOnly = 0;

    /**
     * Cookie constructor.
     * @param $name
     * @param $value
     */
    public function __construct($name, $value) {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @param int $hour
     */
    public function addHour($hour = 1) {
        $this->expiryTime += time() + 3600 * $hour;
    }

    /**
     * @param int $day
     */
    public function addDay($day = 1) {
        $this->expiryTime += time() + 86400 * $day;
    }

    /**
     * @param int $month
     */
    public function addMonth($month = 1) {
        $this->expiryTime += time() + 2592000 * $month;
    }

    /**
     * @param int $year
     */
    public function addYear($year = 1) {
        $this->expiryTime += time() + 31536000 * $year;
    }

    /**
     * @param $time
     */
    public function setOtherExpiryTime($time) {
        $this->expiryTime = $time;
    }

    /**
     * @param string $path
     */
    public function setPath($path) {
        $this->path = $path;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain) {
        $this->domain = $domain;
    }

    /**
     * @param int $secure
     */
    public function setSecure($secure) {
        $this->secure = $secure;
    }

    /**
     * @param int $httpOnly
     */
    public function setHttpOnly($httpOnly) {
        $this->httpOnly = $httpOnly;
    }
}