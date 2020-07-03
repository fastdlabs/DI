<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @link      https://www.github.com/fastdlabs
 * @link      https://www.fastdlabs.com/
 */

namespace FastD\DI;


use Closure;
use FastD\Container\Container;
use ReflectionClass;
use ReflectionException;

/**
 * Class Injection
 *
 * @package FastD\Container
 */
class Factory
{
    /**
     * @var Container
     */
    protected Container $container;

    /**
     * @var string
     */
    protected $object;

    /**
     * @var string
     */
    protected string $method;

    /**
     * @var bool
     */
    protected bool $isStatic = false;

    /**
     * @var array
     */
    protected array $arguments = [];

    /**
     * Injection constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $service
     * @return Factory
     */
    public function bind(string $service): Factory
    {
        $this->object = $service;

        $this->withConstruct();

        return $this;
    }

    public function bindClosure(Closure $service): Factory
    {
        $this->object = $service;

        return $this;
    }

    /**
     * @return Factory
     */
    public function withConstruct(): Factory
    {
        return $this->withMethod('__construct');
    }

    /**
     * @param string $name
     * @param bool $isStatic
     * @return $this
     */
    public function withMethod(string $name, bool $isStatic = false): Factory
    {
        $this->method = $name;

        $this->isStatic = $isStatic;

        return $this;
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function withArguments(array $arguments): Factory
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @param array $arguments
     * @return object
     * @throws ReflectionException
     */
    public function newInstance(array $arguments = []): object
    {
        return (new ReflectionClass($this->object))->newInstanceArgs($arguments);
    }

    /**
     * @param $args
     * @return object
     * @throws ReflectionException
     */
    public function make(...$arguments): object
    {
        if (empty($this->arguments)) {
            $injections = is_callable($this->object) ? detectionClosureArgs($this->object) : detectionObjectArgs($this->object, $this->method);

            foreach ($injections as $injection) {
                $this->arguments[] = $this->container->get($injection);
            }
        }

        $arguments = array_merge($this->arguments, $arguments);

        if (is_callable($this->object)) {
            return new class($this->object) implements AnonymousInterface{
                protected Closure $obj;
                public function __construct(Closure $closure)
                {
                    $this->obj = $closure;
                }
                public function __invoke(...$args)
                {
                    return call_user_func_array($this->obj, $args);
                }
            };
        }

        if ($this->isStatic) {
            return call_user_func_array($this->object . '::' . $this->method, $arguments);
        }

        if ('__construct' === $this->method) {
            return $this->newInstance($arguments);
        }

        $obj = $this->object;

        if (!is_object($obj)) {
            $obj = new $obj;
        }

        if (empty($this->method)) {
            return $obj;
        }

        return call_user_func_array([$obj, $this->method], $arguments);
    }
}
