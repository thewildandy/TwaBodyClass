<?php

namespace TwaBodyClass\View\Helper;

use Zend\View\Helper\AbstractHelper;

class BodyClass extends AbstractHelper
{
    protected $classes;

    public function __construct($classes = array())
    {
        $this->classes = $classes;
    }

    public function __toString()
    {
        return implode(' ', $this->classes);
    }
}