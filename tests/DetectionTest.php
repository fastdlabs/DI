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

class DetectionTest extends TestCase
{
    public function testDetectionObjectArgs()
    {
        list($obj) = detectionObjectArgs(Factory::class, '__construct');
        $this->assertEquals(\FastD\Container\Container::class, $obj);
    }

    public function testDetectionClosureArgs()
    {
        $anonymous = function (\FastD\Container\Container $container) {};
        list($obj) = detectionClosureArgs($anonymous);
        $this->assertEquals(\FastD\Container\Container::class, $obj);
    }
}
