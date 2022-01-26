<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

// @codingStandardsIgnoreFile
class AttributesExtractor implements Extractor
{
    final public function __construct(
        private readonly ReflectionClass|ReflectionClassConstant|ReflectionProperty|ReflectionMethod|ReflectionFunction|ReflectionParameter $reflection,
        private readonly ?string $attribute = null,
        private readonly int $flags = 0
    ) {
    }

    public function extract(): Collection
    {
        return
            new Collection(...array_map(
                fn(ReflectionAttribute $attribute) => new ParsedAttribute($attribute, $this->reflection),
                $this->reflection->getAttributes($this->attribute, $this->flags)
            ));
    }

    public static function fromProperty(
        string|object $objectOrClass,
        string $property,
        ?string $attribute = null,
        int $flags = 0
    ): Collection {
        return (new self(new ReflectionProperty($objectOrClass, $property), $attribute, $flags))->extract();
    }

    public static function fromProperties(
        string|object $objectOrClass,
        ?string $attribute = null,
        int $flags = 0,
        ?int $filter = null
    ): Collection {
        $collection = new Collection;
        foreach ((new ReflectionClass($objectOrClass))->getProperties($filter) as $property) {
            $collection = $collection->merge((new self($property, $attribute, $flags))->extract());
        }

        return $collection;
    }

    public static function fromClass(
        string|object $objectOrClass,
        ?string $attribute = null,
        int $flags = 0
    ): Collection {
        return (new self(new ReflectionClass($objectOrClass), $attribute, $flags))->extract();
    }

    public static function fromClasses(
        iterable $objectOrClasses,
        ?string $attribute = null,
        int $flags = 0
    ): Collection {
        $collection = new Collection;
        foreach ($objectOrClasses as $objectOrClass) {
            $collection = $collection->merge(
                (new self(new ReflectionClass($objectOrClass), $attribute, $flags))->extract()
            );
        }

        return $collection;
    }

    public static function fromClassConstant(
        string|object $objectOrClass,
        string $constant,
        ?string $attribute = null,
        int $flags = 0
    ): Collection {
        return (new self(new ReflectionClassConstant($objectOrClass, $constant), $attribute, $flags))->extract();
    }

    public static function fromClassConstants(
        string|object $objectOrClass,
        ?string $attribute = null,
        int $flags = 0,
        ?int $filter = null
    ): Collection {
        $collection = new Collection;
        foreach ((new ReflectionClass($objectOrClass))->getReflectionConstants($filter) as $constant) {
            $collection = $collection->merge((new self($constant, $attribute, $flags))->extract());
        }

        return $collection;
    }

    public static function fromMethod(
        string|object $objectOrClass,
        string $method,
        ?string $attribute = null,
        int $flags = 0
    ): Collection {
        return (new self(new ReflectionMethod($objectOrClass, $method), $attribute, $flags))->extract();
    }

    public static function fromMethods(
        string|object $objectOrClass,
        ?string $attribute = null,
        int $flags = 0,
        ?int $filter = null
    ): Collection {
        $collection = new Collection;
        foreach ((new ReflectionClass($objectOrClass))->getMethods($filter) as $method) {
            $collection = $collection->merge((new self($method, $attribute, $flags))->extract());
        }

        return $collection;
    }

    public static function fromMethodParameters(
        string|object $objectOrClass,
        string $method,
        ?string $attribute = null,
        int $flags = 0
    ): Collection {
        $collection = new Collection;
        foreach ((new ReflectionMethod($objectOrClass, $method))->getParameters() as $parameter) {
            $collection = $collection->merge((new self($parameter, $attribute, $flags))->extract());
        }

        return $collection;
    }

    public static function fromParameters(
        string|object $objectOrClass,
        ?string $attribute = null,
        int $flags = 0,
        ?int $filterMethod = null
    ): Collection {
        $collection = new Collection;
        foreach ((new ReflectionClass($objectOrClass))->getMethods($filterMethod) as $method) {
            foreach ($method->getParameters() as $parameter) {
                $collection = $collection->merge((new self($parameter, $attribute, $flags))->extract());
            }
        }

        return $collection;
    }

    public static function all(
        string|object $objectOrClass,
        ?string $attribute = null,
        int $flags = 0,
    ): Collection {
        return (new Collection())
            ->merge(self::fromClass($objectOrClass, $attribute, $flags))
            ->merge(self::fromClassConstants($objectOrClass, $attribute, $flags))
            ->merge(self::fromProperties($objectOrClass, $attribute, $flags))
            ->merge(self::fromMethods($objectOrClass, $attribute, $flags))
            ->merge(self::fromParameters($objectOrClass, $attribute, $flags));
    }
}
