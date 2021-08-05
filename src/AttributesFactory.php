<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use FlexFqcnFinder\FqcnFinderInterface;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Attribute;

class AttributesFactory
{
    public static function from(string|array $reflectionObject, string $attribute = null, int $flags = 0): Attributes
    {
        $attributes = [];
        $reflectionObject = is_array($reflectionObject) ? $reflectionObject : [$reflectionObject];
        foreach ($reflectionObject as $object) {
            if ($object instanceof ReflectionClass ||
                $object instanceof ReflectionMethod ||
                $object instanceof ReflectionProperty ||
                $object instanceof ReflectionParameter ||
                $object instanceof ReflectionClassConstant
            ) {
                foreach ($object->getAttributes($attribute, $flags) as $reflectionAttribute) {
                    $attributes[] = new ParsedAttribute($reflectionAttribute, $object);
                }
            }
        }

        return new Attributes(...$attributes);
    }

    public static function fromClass(
        string|array $objectOrClass,
        int $target = Attribute::TARGET_ALL,
        string $attribute = null,
        int $flags = 0
    ): Attributes {
        $reflectionObjects = [];
        $objectOrClass = is_array($objectOrClass) ? $objectOrClass : [$objectOrClass];
        foreach ($objectOrClass as $class) {
            $reflectionClass = new ReflectionClass($class);
            self::parseClass($reflectionClass, $reflectionObjects, $target);
        }

        return AttributesFactory::from($reflectionObjects, $attribute, $flags);
    }

    protected static function parseClass(
        ReflectionClass $reflectionClass,
        array &$reflectionObjects,
        int $target
    ): void {
        if ($target & Attribute::TARGET_CLASS) {
            $reflectionObjects[] = $reflectionClass;
        }

        self::parseProperties($reflectionClass, $reflectionObjects, $target);
        self::parseMethodsAndParameters($reflectionClass, $reflectionObjects, $target);
        self::parseClassConstants($reflectionClass, $reflectionObjects, $target);
    }

    protected static function parseProperties(
        ReflectionClass $reflectionClass,
        array &$reflectionObjects,
        int $target
    ): void {
        if ($target & Attribute::TARGET_PROPERTY) {
            foreach ($reflectionClass->getProperties() as $property) {
                $reflectionObjects[] = $property;
            }
        }
    }

    protected static function parseMethodsAndParameters(
        ReflectionClass $reflectionClass,
        array &$reflectionObjects,
        int $target
    ): void {
        foreach ($reflectionClass->getMethods() as $method) {
            if ($target & Attribute::TARGET_METHOD) {
                $reflectionObjects[] = $method;
            }
            if ($target & Attribute::TARGET_PARAMETER) {
                foreach ($method->getParameters() as $parameter) {
                    $reflectionObjects[] = $parameter;
                }
            }
        }
    }

    protected static function parseClassConstants(
        ReflectionClass $reflectionClass,
        array &$reflectionObjects,
        int $target
    ): void {
        if ($target & Attribute::TARGET_CLASS_CONSTANT) {
            foreach ($reflectionClass->getReflectionConstants() as $constant) {
                $reflectionObjects[] = $constant;
            }
        }
    }

    public static function fromFqcnFinder(
        FqcnFinderInterface $finder,
        int $target = Attribute::TARGET_ALL,
        string $attribute = null,
        int $flags = 0
    ): Attributes {
        return AttributesFactory::fromClass($finder->find(), $target, $attribute, $flags);
    }
}
