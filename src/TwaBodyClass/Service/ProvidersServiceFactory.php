<?php

namespace TwaBodyClass\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProvidersServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config    = $serviceLocator->get('twabodyclass_module_options');
        $providers = array();

        foreach($config->getProviders() as $provider => $config) {
            if($serviceLocator->has($provider)) {
                $provider = $serviceLocator->get($provider);
            } else {
                $provider = new $provider($config);
            }

            $providers[] = $provider;
        }

        return $providers;
    }
}