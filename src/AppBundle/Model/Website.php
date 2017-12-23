<?php

namespace AppBundle\Model;

use Exception;

class Website
{
    private $domain;
    private $time;

    public function __construct($domain = "")
    {
        $this->domain = (string) $domain;
        $this->time = 0;
    }

    public function __toString()
    {
        return $this->domain . " : " . $this->getTime() . " ms";
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }

}
