<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

use FastD\Container\Container;
use FastD\DI\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testClass()
    {
        $container = new Container();
        $container->add('container', $container);
        $factory = new Factory($container);
        $factory->bind(Factory::class);
        $factory = $factory->make();
        $this->assertInstanceOf(Factory::class, $factory);
    }

    public function testClosure()
    {
        $container = new Container();
        $container->add('container', $container);
        $factory = new Factory($container);
        $factory->bindClosure(function (Container $container) {
            return $container;
        });
        $closure = $factory->make()();
        $this->assertInstanceOf(Container::class, $closure);
    }

    public function testArguments()
    {
        $container = new Container();
        $container->add('container', $container);
        $factory = new Factory($container);
        $factory->bindClosure(function (Container $container, $name, $age) {
            return "name: " . $name . ' age: ' . $age;
        });
        $closure = $factory->make('foo', 18);
        echo $closure();
        $this->expectOutputString(sprintf("name: foo age: 18"));
    }
}
