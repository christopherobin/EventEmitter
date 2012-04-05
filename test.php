<?php

require_once('EventEmitter.php');

// basic test handler
function test($test_name, $a, $b) {
    if ($a !== $b) throw new Exception($test_name . ' failed! received: ' . $a . ' / expected: ' . $b);
}

// basic class for testing
class Object implements \events\EventEmitterInterface {
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
    'test1' => 0,
    'test2' => ''
];

$o = new Object();

$o->all(function($event) { echo "all: event $event called\n"; });
// first test, basic events
$o->on('test1', function($data) use(&$checks) { $checks['test1'] = $data; });
$o->test1();
test('basic event', $checks['test1'], 42);

// reset test
$o->removeAllListeners();
$o->test1();
test('listeners removed', $checks['test1'], 42);

// event chaining
$o->all(function($event, $arg1) { echo "all: event $event($arg1) called\n"; });
$o->on('test1', function($data) use(&$checks) { $checks['test1'] += $data; })
  ->on('test1', function($data) use(&$checks) { $checks['test1'] += $data*3; });
$o->test1();
test('event chaining', $checks['test1'], 210);

// reset test
$o->removeAllListeners();

// check once
$o->once('test2', function($data) use(&$checks) { $checks['test2'] .= $data; });
$o->test2();
$o->test2();
test('event chaining', $checks['test2'], 'finished');

echo 'all test successful!' . PHP_EOL;
