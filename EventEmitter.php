<?php

namespace events;

/**
* Provide methods to fire and listen events
*
* @category   Utility
* @copyright  Copyright (c) 2012 Christophe Robin <crobin@nekoo.com>
* @license    MIT License
*/
trait EventEmitter {
    // this is a safe default
    const DEFAULT_MAX_LISTENERS = 10;

    // holds our events and their callbacks (the underscore is to try to
    // prevent name collisions as PHP prevents classes using this trait to
    // declare a property with the same name)
    private $_events = [];
    private $_max_listeners = self::DEFAULT_MAX_LISTENERS;

    public function setMaxListeners($value) {
        $this->_max_listeners = $value;
    }

    /**
    * Emit an event
    *
    * @access protected
    * @param string $event The event name
    * @param string $arg1 An argument provided to the callback
    * @param string $arg... Another argument
    */
    protected function emit() {
        $args = func_get_args();
        $event = array_shift($args);

        if ($event === 'error') {
            if (!isset($this->_events[$event]) || !$this->_events[$event]) {
                if ($args[0] instanceof \Exception) throw $args[0];
                throw new Exception('Uncaught, unspecified \'error\' event.');
            }
        }

        // if no event exists
        if (!isset($this->_events[$event]) || !$this->_events[$event]) return false;
        $handlers = $this->_events[$event];

        // call each handler
        foreach ($handlers as $handler) {
            call_user_func_array($handler, $args);
        }

        return true;
    }

    /**
    * Register a listener
    *
    * @access public
    * @param string $event The event name
    * @param string $handler The callback to call
    */
    public function on($event, callable $handler) {
        if (!isset($this->_events[$event])) $this->_events[$event] = [];
        $this->_events[$event][] = $handler;
        if ($this->_max_listeners && (count($this->_events[$event]) > $this->_max_listeners)) {
            throw new Exception(
                'Possible EventEmitter leak detected. '
                . count($this->_events[$event]) . ' listeners added. '
                . 'Use ' . get_class($this) . '->setMaxListeners() to increase limit.'
            );
        }
        return $this;
    }

    /**
    * Alias to on
    */
    public function addListener() {
        return call_user_func_array(array($this, 'on'), func_get_args());
    }

    /**
    * Register a listener that is called only once
    *
    * @access public
    * @param string $event The event name
    * @param string $handler The callback to call
    */
    public function once($event, callable $handler) {
        $g = function() use ($event, $handler, &$g) {
            $this->off($event, $g);
            $args = func_get_args();
            call_user_func_array($handler, $args);
        };
        $this->on($event, $g);
        return $this;
    }

    /**
    * Unregister a listener
    *
    * @access public
    * @param string $event The event name
    * @param string $handler The callback to unregister
    */
    public function off($event, callable $handler) {
        if (!isset($this->_events[$type]) || !$this->_events[$type]) return $this;
        $key = array_search($handler, $this->_events[$event]);
        if ($key === false) return $this;
        array_splice($this->_events[$event], $key, 1);
        return $this;
    }

    /**
    * Alias to off
    */
    public function removeListener() {
        return call_user_func_array(array($this, 'off'), func_get_args());
    }

    /**
    * Remove all listeners from a given event
    *
    * @access public
    * @param string $event The event name, if none provided, remove all listeners from all events
    */
    public function removeAllListeners($event = null) {
        if (!$event) {
            $this->_events = [];
            return $this;
        }
        if (!isset($this->_events[$event]) || !$this->_events[$event]) return $this;
        $this->_events[$event] = [];
        return $this;
    }

    /**
    * Return all listeners from a given event
    *
    * @access public
    * @param string $event The event name
    */
    public function getListeners($event) {
        if (!isset($this->_events[$event])) $this->_events[$event] = [];
        return $this->_events[$event];
    }
}
