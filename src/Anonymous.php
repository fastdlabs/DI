<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\DI;


use Closure;

abstract class Anonymous
{
    protected Closure $obj;
    protected array $args = [];

    public function __construct(Closure $closure, array $args)
    {
        $this->obj = $closure;
        $this->args = $args;
    }

    public function __invoke(...$args)
    {
        $args = array_merge($this->args, $args);
        return call_user_func_array($this->obj, $args);
    }
}
