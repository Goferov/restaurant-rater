<?php

namespace App;

use Exception;
use ReflectionClass;
use ReflectionMethod;

class Container
{
    private array $bindings = [];

    public function set($id, $factory)
    {
        $this->bindings[$id] = $factory;
    }

    public function get($id)
    {
        if (!isset($this->bindings[$id])) {
            throw new Exception("Target binding [$id] does not exist.");
        }

        $factory = $this->bindings[$id];

        return $factory($this);
    }

    public function build(string $class)
    {
        $reflector = new ReflectionClass($class);
        if (!$reflector->isInstantiable()) {
            throw new Exception("Target [$class] is not instantiable.");
        }

        $constructor = $reflector->getConstructor();
        if ($constructor === null) {
            return new $class;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            if ($type === null || $type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Unresolvable dependency [{$parameter->getName()}] in class {$parameter->getDeclaringClass()->getName()}");
                }
            } else {
                $dependencies[] = $this->get($type->getName());
            }
        }

        return $reflector->newInstanceArgs($dependencies);
    }

    public function callMethod($object, string $method, array $parameters = [])
    {
        $reflector = new ReflectionMethod($object, $method);
        $methodParameters = $reflector->getParameters();
        $dependencies = [];

        foreach ($methodParameters as $parameter) {
            $type = $parameter->getType();
            if ($type === null || $type->isBuiltin()) {
                if (array_key_exists($parameter->getName(), $parameters)) {
                    $dependencies[] = $parameters[$parameter->getName()];
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Unresolvable dependency [{$parameter->getName()}] in method [$method]");
                }
            } else {
                $dependencies[] = $this->get($type->getName());
            }
        }

        return $reflector->invokeArgs($object, $dependencies);
    }
}
