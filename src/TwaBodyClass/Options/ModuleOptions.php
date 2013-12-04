<?php

namespace TwaBodyClass\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    protected $__strictMode__ = false;

    protected $providers = array();

    public function getProviders()
    {
        return $this->providers;
    }

    public function setProviders($providers)
    {
        $this->providers = $providers;
    }
}