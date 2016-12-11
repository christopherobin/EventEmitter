<?php
namespace TwigComposerTests;

use Nekoo\StaticEventEmitter;

/*
 *
 * IMPORTANT:
 * PHP 7.0+ is needed to run this tests
 * because anonymous classes are needed (new class...)
 *
 */


class AClassWithTheTrait
{
    use StaticEventEmitter; // This is NOT an error

    static function emitSomething($event,$data)
    {
        self::emit($event,$data);
    }
};

class StaticEventEmitterTest extends \PHPUnit_Framework_TestCase
{
    protected $AClassWithTheTrait;

    protected function createStubObjectWithACoupleOfMethods($methods)
    {
        return $this->getMockBuilder('stdClass')
            ->setMethods($methods)
            ->getMock();
    }


    protected function createAClassWithTheTrait()
    {
        $class = null;
        // We need to use a random number because, if not, PHP creates the same class if the code is the same.
        // With a random number and microtime it is supossed we always will have different code
        // and the returned class will be a new one.
        $stamp = microtime().random_int(PHP_INT_MIN ,PHP_INT_MAX);
        $classcode = <<< EOT
        \$class = new class {
            use Nekoo\StaticEventEmitter; // This is NOT an error: it works

            static function emitSomething(\$event,\$data)
            {
                static::emit(\$event,\$data);
            }
            public static \$differentiate='$stamp';
        };
EOT;
        eval($classcode);
        return $class;
    }

    public function setUp()
    {
        parent::setUp();

        // We need to create the class dinamically in each test
        // so we can start with a clean state
        $this->AClassWithTheTrait =  $this->createAClassWithTheTrait();
    }

    function tearDown()
    {
        parent::tearDown();
        $this->AClassWithTheTrait = null;
    }

    public function test_It_Can_Registers_Listeners_With_On()
    {
        $stub = $this->createStubObjectWithACoupleOfMethods(['method1','method2']);

        $callables = [
            [$stub,'method1'],
            function(){
            }
        ];

        ($this->AClassWithTheTrait)::on('event',$callables[0]);
        $listeners = ($this->AClassWithTheTrait)::getListeners('event');
        $this->assertEquals($listeners[0],$callables[0]);

        ($this->AClassWithTheTrait)::on('event',$callables[1]);
        $listeners = ($this->AClassWithTheTrait)::getListeners('event');
        $this->assertEquals($listeners,$callables,"\$canonicalize = true"); // canonicalize: Undocumented parameter so it does not check order of elements
    }

    public function test_It_Can_Registers_Listeners_With_addListener()
    {
        $stub = $this->createStubObjectWithACoupleOfMethods(['method1','method2']);

        $callables = [
            [$stub,'method1'],
            function(){
            }
        ];

        ($this->AClassWithTheTrait)::addListener('event',$callables[0]);
        $listeners = ($this->AClassWithTheTrait)::getListeners('event');
        $this->assertEquals($listeners[0],$callables[0]);

        ($this->AClassWithTheTrait)::addListener('event',$callables[1]);
        $listeners = ($this->AClassWithTheTrait)::getListeners('event');
        $this->assertEquals($listeners,$callables,"\$canonicalize = true"); // canonicalize: Undocumented parameter so it does not check order of elements
    }

    public function test_It_Can_Remove_Listeners_With_Off()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_It_Can_Remove_Listeners_With_removeListener()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test_It_Can_Not_Register_More_Listener_Than_MaxListeners()
    {
        ($this->AClassWithTheTrait)::setMaxListeners(3);
        ($this->AClassWithTheTrait)::on('whatever',function(){});
        ($this->AClassWithTheTrait)::on('whatever',function(){});
        ($this->AClassWithTheTrait)::on('otherevent',function(){});
        ($this->AClassWithTheTrait)::on('whatever',function(){});

        $this->expectException ( \Exception :: class ) ;
        ($this->AClassWithTheTrait)::on('whatever',function(){});
    }

    public function test_It_Emitts_Events()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

}