<?php

function detectionObjectArgs(string $obj, string $method): array
{
    $reflection = new ReflectionMethod($obj, $method);
    return detectionArgs($reflection);
}

function detectionClosureArgs(Closure $closure): array
{
    $reflection = new ReflectionFunction($closure);
    return detectionArgs($reflection);
}

function detectionArgs(ReflectionFunctionAbstract $reflectionFunctionAbstract): array
{
    if (0 >= $reflectionFunctionAbstract->getNumberOfParameters()) {
        unset($reflectionFunctionAbstract);
        return [];
    }

    $args = [];
    foreach ($reflectionFunctionAbstract->getParameters() as $index => $parameter) {;
        if (($class = $parameter->getClass()) instanceof ReflectionClass) {
            $args[$index] = $class->getName();
        }
    }
    unset($reflectionFunctionAbstract);
    return $args;
}
