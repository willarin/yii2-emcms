<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Config extends \Codeception\Module
{
    protected $config = [];

    public function getConfig($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        } else {
            return null;
        }
    }

}
