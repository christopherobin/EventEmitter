# EventEmitter

This is a direct port of the [_EventEmitter_](https://github.com/joyent/node/blob/master/lib/events.js) class from [node.js](https://github.com/joyent/node/) using PHP 5.4 traits.

## Why PHP 5.4?

Due to the nature of the _EventEmitter_ functionnality, using simple extends sounds like the wrong way to deal with it as it is a set of functionnality to add to an existing class instead of your class extending the functionnalities of EventEmitter.
As such traits are the best way to implement this kind of functionnality and those are only available to PHP 5.4+.

## How to Use

```php
<?php

// if using directly
require_once('EventEmitter.php');

// if using through composer just use the autoload
require_once('vendor\.composer\autoload.php');

// test class
class Item {
  use \Nekoo\EventEmitter;

  public function register($infos) {
    // do something
    // fire the event
    $this->emit('register', $infos);
  }
}

// allows you to check if a class uses the event emitter through
// $class instanceof \Nekoo\EventEmitterInterface
class AnotherItem implements \Nekoo\EventEmitterInterface {
  use \Nekoo\EventEmitter;
}

// create an instance of our object
$i = new Item();
// register an observer for the register event
$i->on('register', function($infos) {
    echo "Item registered!\n";
    var_dump($infos);
});
// call the method
$i->register(array('key' => 'value'));
```

You can also use a class as an EventEmitter, withouth the need to instantiate an object. The API is the same as object emitters: 

```php
<?php

class EmitThings {
  use \Nekoo\StaticEventEmitter;

  public static function register($infos) {
    // do something
    // fire the event
    static::emit('register', $infos);
  }
}

// register an observer for the register event
EmitThings::on('register', function($infos) {
    echo "Item registered!\n";
    var_dump($infos);
});
// call the method
EmitThings::register(array('key' => 'value'));
```

## API

* [setMaxListeners(int)](https://github.com/christopherobin/EventEmitter/blob/master/src/EventEmitter.php#L20)<br>
  Set the maximum listeners allowed by events, default is 10. Adding too many listeners to a same event on the
  same object will make fireing events slower and slower, just up this limit if needed.<br>

```php
<?php
$object->setMaxListeners(20);
```
* [emit(string[, mixed arg1, ...])](https://github.com/christopherobin/EventEmitter/blob/master/src/EventEmitter.php#L24)<br>
  Emit an event, all arguments provided after the event name are sent to the callback as is.<br>

```php
<?php
$object->emit('event', $foo, $bar);
```
* [on(string, callable)](https://github.com/christopherobin/EventEmitter/blob/master/src/EventEmitter.php#L61)<br>
  Register a callback for en event, every forms of callbacks are accepted (strings, arrays or closures).<br>

```php
<?php
$object->on('event', function() { var_dump(func_get_args()); });
```
* [addListener(string, callable)](https://github.com/christopherobin/EventEmitter/blob/master/src/EventEmitter.php#L81)<br>
  Alias for _on()_
* [all(callable)](https://github.com/christopherobin/EventEmitter/blob/master/src/EventEmitter.php#L88)<br>
  Register a callback for en event, every forms of callbacks are accepted (strings, arrays or closures).<br>

```php
<?php
$object->all(function($event, $arg1) { var_dump($event, $arg1); });
```
* [once(string, callable)](https://github.com/christopherobin/EventEmitter/blob/master/src/EventEmitter.php#L98)<br>
  Same thing as _on()_ but the listener will only be called once.
* [off(string, callable)](https://github.com/christopherobin/EventEmitter/blob/master/src/EventEmitter.php#L115)<br>
  Removes a listener for this event. You need to provide the very same array or string,
  or if using a closure the same instance.<br>

```php
<?php

$fun = function($arg1) { echo "event: $arg1\n"; };
$object->on('event', $fun); // adds the event
$object->off('event', $fun); // remove the event
```
* [removeListener(string, callable)](https://github.com/christopherobin/EventEmitter/blob/master/src/EventEmitter.php#L130)<br>
  Alias for _off()_
* [removeAllListeners([string])](https://github.com/christopherobin/EventEmitter/blob/master/src/EventEmitter.php#L137)<br>
  Removes all listeners from a given event, or all listeners from all events if no event provided.

```php
<?php
$object->removeAllListeners('event'); // only for the 'event' event
$object->removeAllListeners(); // for every events on the object
```
* [getListeners(string)](https://github.com/christopherobin/EventEmitter/blob/master/src/EventEmitter.php#L153)<br>
  Returns an array with the listeners for this event

```php
<?php
foreach ($object->getListeners('event') as $listener) {
  var_dump($listener);
}
```