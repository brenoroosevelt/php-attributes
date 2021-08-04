<?php
declare(strict_types=1);

namespace BrenoRosevelt\PhpAttributes;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Attribute;

class AttributesFactory
{
    public static function from(array $reflectionObject, string $attribute = null, int $flags = 0): Attributes
    {
        $attributes = [];
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

            if ($target & Attribute::TARGET_CLASS) {
                $reflectionObjects[] = $reflectionClass;
            }

            if (($target & Attribute::TARGET_METHOD) || ($target & Attribute::TARGET_PARAMETER)) {
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

            if ($target & Attribute::TARGET_PROPERTY) {
                foreach ($reflectionClass->getProperties() as $property) {
                    $reflectionObjects[] = $property;
                }
            }

            if ($target & Attribute::TARGET_CLASS_CONSTANT) {
                foreach ($reflectionClass->getReflectionConstants() as $constant) {
                    $reflectionObjects[] = $constant;
                }
            }
        }

        return AttributesFactory::from($reflectionObjects, $attribute, $flags);
    }
}
