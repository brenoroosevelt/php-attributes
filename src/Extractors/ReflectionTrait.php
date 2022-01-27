<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Exception\ClassConstantDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\ClassDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\FunctionDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\MethodDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\PropertyDoesNotExists;
use Closure;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;

trait ReflectionTrait
{
    /**
     * @throws MethodDoesNotExists
     */
    protected function getMethodOrFail(ReflectionClass $reflectionClass, string $method): ReflectionMethod
    {
        try {
            return $reflectionClass->getMethod($method);
        } catch (ReflectionException $e) {
            throw MethodDoesNotExists::forMethod($reflectionClass->getName(), $method, $e);
        }
    }

    /**
     * @throws PropertyDoesNotExists
     */
    protected function getPropertyOrFail(ReflectionClass $reflectionClass, string $property): ReflectionProperty
    {
        try {
            return $reflectionClass->getProperty($property);
        } catch (ReflectionException $e) {
            throw PropertyDoesNotExists::forProperty($reflectionClass->getName(), $property, $e);
        }
    }

    /**
     * @throws ClassConstantDoesNotExists
     */
    protected function getConstantOrFail(ReflectionClass $reflectionClass, string $constant): ReflectionClassConstant
    {
        if (!$reflectionClass->hasConstant($constant)) {
            throw ClassConstantDoesNotExists::forClassConstant($reflectionClass->getName(), $constant);
        }

        return $reflectionClass->getReflectionConstant($constant);
    }

    /**
     * @throws ClassDoesNotExists
     */
    protected function getClassOrFail(string|object $classOrObject): ReflectionClass
    {
        try {
            return new ReflectionClass($classOrObject);
        } catch (ReflectionException $e) {
            throw ClassDoesNotExists::forObjecOrClass($classOrObject, $e);
        }
    }

    /**
     * @throws FunctionDoesNotExists
     */
    protected function getFunctionOrFail(string|Closure $function): ReflectionFunction
    {
        try {
            return new ReflectionFunction($function);
        } catch (ReflectionException $e) {
            throw FunctionDoesNotExists::forFunction($function, $e);
        }
    }
}
