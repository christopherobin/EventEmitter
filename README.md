# EventEmitter

This is a direct port of the [_EventEmitter_](https://github.com/joyent/node/blob/master/lib/events.js) class from [node.js](https://github.com/joyent/node/) to using PHP 5.4 traits.

## Why PHP 5.4?

Due to the nature of the EventEmitter functionnality, using simple extends sounds like the wrong way to deal with it as it is a set of functionnality to add to an existing class instead of your class extending the functionnalities of the EventEmitter.

As such traits are the best way to implement this kind of functionnality and those are only available to PHP 5.4+.

## How to Use

```php
<?php

require_once('EventEmitter.php');

// test class
class Item {
  use \events\EventEmitter;

  public function register($infos) {
    // do something
    // fire the event
    $this->emit('register', $infos);
  }
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