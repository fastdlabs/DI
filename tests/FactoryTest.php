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
        try {
            $factory = $factory->make();
            $this->assertInstanceOf(Factory::class, $factory);
        } catch (Exception $e) {

        }
    }

    public function testClosure()
    {
        $container = new Container();
        $container->add('container', $container);
        $factory = new Factory($container);
        $factory->bindClosure(function (Container $container) {
            return $container;
        });
        $closure = $factory->make();
        $container = $closure;
        $this->assertInstanceOf(Container::class, $container);
    }
}
