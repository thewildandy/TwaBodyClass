<?php

namespace TwaBodyClass;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\ListenerAggregateInterface;

class Module
{
    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getTarget();
        $serviceManager = $app->getServiceManager();
        $providers = $serviceManager->get('TwaBodyClass\Providers');

        foreach($providers as $provider) {
            if($provider instanceof ListenerAggregateInterface) 
                $app->getEventManager()->attach($provider);
        }
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'twabodyclass_module_options' => function($sm) {
                    $config = $sm->get('config');
                    return new Options\ModuleOptions(array_key_exists('twabodyclass', $config) ? $config['twabodyclass'] : array());
                }
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'bodyClass' => function($pm) {
                    $classes = array();
                    $providers = $pm->getServiceLocator()->get('TwaBodyClass\Providers');
                    foreach($providers as $provider) { 
                        $classes = array_merge($provider->getClasses(), $classes);
                    }
                    return new View\Helper\BodyClass($classes);
                }
            )
        );
    }
}