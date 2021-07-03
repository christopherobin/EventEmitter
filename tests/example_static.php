<?php

require_once('../src/Nekoo/EventEmitter.php');
require_once('../src/Nekoo/StaticEventEmitter.php');

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