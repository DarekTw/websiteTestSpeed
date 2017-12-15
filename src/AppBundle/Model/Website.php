<?php

namespace AppBundle\Model;

use Exception;

class Website
{
    private $domain;
    private $time;
    private $error;

    public function __construct($domain = "")
    {
        $this->domain = (string) $domain;
        $this->time = 0;
    }

    public function __toString()
    {
        return $this->domain . " : " . $this->getTime() . "s.";
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    public function benchmark()
    {
        try {
            $time = microtime(true);
            file_get_contents($this->domain);
            $this->time = microtime(true) - $time;
        } catch (Exception $ex) {
            $this->time = 999999;
            $this->error = "The specified domain can not be examined, please check the correctness of the address.";
        }
    }

}
