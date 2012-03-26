<?php

require_once('EventEmitter.class.php');

// basic assert
function assert($test_name, $a, $b) {
    if ($a !== $b) throw new Exception($test_name . ' failed! received: ' . $a . ' / expected: ' . $b);
}

// basic class for testing
class Object {
    use \events\EventEmitter;

    public function test1() {
        $this->emit('test1', 42);
    }

    public function test2() {
        $this->emit('test2', 'finished');
    }
}

// used to verify that events were fired
$checks = [
    'test1' => 0
];

$o = new Object();
$o->on('test1', function($data) use(&$checks) { $checks['test1'] = $data; });
assert('basic event', $checks['test1'], 42);

echo 'all test successful!' . PHP_EOL;
