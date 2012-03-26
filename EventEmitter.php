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
        private $events = [];

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
                $type = array_shift($args);

                // if no event exists
                if (!isset($this->events[$type])) return false;
                $handlers = $this->events[$type];

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
                if (!is_callable($handler)) return false;
                if (!isset($this->events[$event])) $this->events[$event] = [];
                $this->events[$event][] = $handler;
                return true;
        }

        /**
         * Register a listener that is called only once
         *
         * @access public
         * @param string $event The event name
         * @param string $handler The callback to call
         */
        public function once($event, callable $handler) {
                $ee = $this;
                $g = function() use ($event, $handler, &$g) {
                        $this->off($event, $g);
                        $args = func_get_args();
                        call_user_func_array($handler, $args);
                };
                $this->on($event, $g);
        }

        /**
         * Unregister a listener
         *
         * @access public
         * @param string $event The event name
         * @param string $handler The callback to unregister
         */
        public function off($event, callable $handler) {
                $key = array_search($handler, $this->events[$event]);
                if ($key === false) return false;
                array_splice($this->events[$event], $key, 1);
        }
}
