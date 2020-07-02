<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

use FastD\DI\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testDI()
    {
        $factory = new Factory(new \FastD\Container\Container());
        $factory->bind(Factory::class);
        try {
            $factory->make();
        } catch (Exception $e) {

        }
    }
}
