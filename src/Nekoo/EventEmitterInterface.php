<?php

namespace Nekoo;

/**
* This interface allows the developer to check whether EventEmitter is used on a class or not
* Checking if the trait is here is unreliable as the developer may have aliased methods, this
* interface prevent this.
*/
interface EventEmitterInterface {
    public function setMaxListeners($value);
    public function on($event, callable $handler);
    public function addListener();
    public function once($event, callable $handler);
    public function all(callable $handler);
    public function off($event, callable $handler);
    public function removeListener();
    public function removeAllListeners($event);
    public function getListeners($event);
}
