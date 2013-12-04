<?php

namespace TwaBodyClass\Provider;

use Zend\Authentication\AuthenticationService;

class AuthIdentity implements ProviderInterface
{
    protected $authService;

    protected $options = array(
        'has_identity_class' => 'logged-in',
        'no_identity_class' => 'not-logged-in'
    );

    public function __construct($options = array())
    {
        foreach($options as $key => $value) {
            $this->options[$key] = $value;
        }
    }

    public function getClasses()
    {
        $class = $this->getAuthService()->hasIdentity() ? $this->options['has_identity_class'] : $this->options['no_identity_class'];
        return array($class);
    }

    public function getAuthService()
    {
        return $this->authService;
    }

    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }
}