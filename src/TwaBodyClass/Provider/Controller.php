<?php

namespace TwaBodyClass\Provider;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;

class Controller implements ProviderInterface, ListenerAggregateInterface
{
    protected $assignments = array();

    protected $classes = array();

    protected $listeners = array();

    public function __construct($assignments)
    {
        foreach($assignments as $controller => $actions)
        {
            if(!array_key_exists($controller, $this->assignments))
                $this->assignments[$controller] = array();

            foreach($actions as $action => $classes) {
                if(is_string($classes))
                    $classes = explode(' ', $classes);

                if(!array_key_exists($action, $this->assignments[$controller]))
                    $this->assignments[$controller][$action] = array();

                $this->assignments[$controller][$action] = array_merge(
                    $classes,
                    $this->assignments[$controller][$action]
                );
            }
        }
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'), -1000);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach($this->listeners as $index => $listener) {
            if($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function onDispatch(MvcEvent $event)
    {
        $match      = $event->getRouteMatch();
        $controller = $match->getParam('controller');
        $action     = $match->getParam('action');

        $classes = array();

        if(!array_key_exists($controller, $this->assignments))
            return;

        if(array_key_exists('*', $this->assignments[$controller]))
            $classes = $this->assignments[$controller]['*'];

        if(array_key_exists($action, $this->assignments[$controller]))
            $classes = array_merge($this->assignments[$controller][$action], $classes);

        $this->classes = $classes;
        return;
    }
}