<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Attribute;

class AttributesFactory
{
    /**
     * @param object|string|object[]|string[]|array $objectOrClass
     * @param int $target
     * @param string|null $attribute
     * @param int $flags
     * @return Collection
     */
    public function from(
        object|string|array $objectOrClass,
        int $target = Attribute::TARGET_ALL,
        string $attribute = null,
        int $flags = 0,
    ): Collection {
        $collection = new Collection();

        $classes =
            is_array($objectOrClass) ?
                array_map(fn($class) => $this->reflectionClass($class), $objectOrClass) :
                [$this->reflectionClass($objectOrClass)];

        foreach ($classes as $objectOrClass) {
            if (Attribute::TARGET_CLASS & $target) {
                $collection = $collection->merge($this->fromClass($objectOrClass, $attribute, $flags));
            }

            if (Attribute::TARGET_PROPERTY & $target) {
                $collection = $collection->merge($this->fromProperties($objectOrClass, $attribute, $flags));
            }

            if (Attribute::TARGET_METHOD & $target) {
                $collection = $collection->merge($this->fromMethods($objectOrClass, $attribute, $flags));
            }

            if (Attribute::TARGET_PARAMETER & $target) {
                $collection =
                    $collection->merge($this->fromParameters($objectOrClass, null, $attribute, $flags));
            }

            if (Attribute::TARGET_CLASS_CONSTANT & $target) {
                $collection = $collection->merge($this->fromConstants($objectOrClass, $attribute, $flags));
            }
        }

        return $collection;
    }

    /**
     * @param object|object[] $reflectionObject
     * @param string|null $attribute
     * @param int $flags
     * @return Collection
     */
    public function fromReflection(object|array $reflectionObject, string $attribute = null, int $flags = 0): Collection
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

        return new Collection(...$attributes);
    }

    public function fromClass(
        object|string $objectOrClass,
        string $attribute = null,
        int $flags = 0,
    ): Collection {
        $objectOrClass = $this->reflectionClass($objectOrClass);
        return $this->fromReflection($objectOrClass, $attribute, $flags);
    }

    public function fromProperties(
        object|string $objectOrClass,
        string $attribute = null,
        int $flags = 0,
        int $filter = null
    ): Collection {
        $collection = new Collection();
        $objectOrClass = $this->reflectionClass($objectOrClass);
        foreach ($objectOrClass->getProperties($filter) as $property) {
            $collection = $collection->merge($this->fromReflection($property, $attribute, $flags));
        }

        return $collection;
    }

    public function fromMethods(
        object|string $objectOrClass,
        string $attribute = null,
        int $flags = 0,
        int $filter = null
    ): Collection {
        $collection = new Collection();
        $objectOrClass = $this->reflectionClass($objectOrClass);
        foreach ($objectOrClass->getMethods($filter) as $method) {
            $collection = $collection->merge($this->fromReflection($method, $attribute, $flags));
        }

        return $collection;
    }

    public function fromParameters(
        object|string $objectOrClass,
        string $method = null,
        string $attribute = null,
        int $flags = 0,
        int $filter = null
    ): Collection {
        $collection = new Collection();
        $objectOrClass = $this->reflectionClass($objectOrClass);
        $methods = is_null($method) ? $objectOrClass->getMethods($filter) : [$objectOrClass->getMethod($method)];
        foreach ($methods as $method) {
            foreach ($method->getParameters() as $parameter) {
                $collection = $collection->merge($this->fromReflection($parameter, $attribute, $flags));
            }
        }

        return $collection;
    }

    public function fromConstants(
        object|string $objectOrClass,
        string $attribute = null,
        int $flags = 0,
        int $filter = null
    ): Collection {
        $collection = new Collection();
        $objectOrClass = $this->reflectionClass($objectOrClass);
        foreach ($objectOrClass->getReflectionConstants($filter) as $constant) {
            $collection = $collection->merge($this->fromReflection($constant, $attribute, $flags));
        }

        return $collection;
    }

    protected function reflectionClass(object|string $objectOrClass): ReflectionClass
    {
        if ($objectOrClass instanceof ReflectionClass) {
            return $objectOrClass;
        }

        return new ReflectionClass($objectOrClass);
    }
}
