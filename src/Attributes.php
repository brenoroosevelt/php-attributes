<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionParameter;
use function is_array;
use function is_string;
use function is_object;
use function in_array;
use function array_map;
use function array_filter;
use function array_push;
use function array_walk;
use function array_walk_recursive;
use function mb_strtolower;

class Attributes
{
    final public function __construct(private readonly ?string $attribute = null, private readonly int $flag = 0)
    {
    }

    public static function extract(string $attribute = null, int $flag = 0): self
    {
        return new self($attribute, $flag);
    }

    /** @return ReflectionClass[] */
    private function normalize(string|object|array $objectOrClass): array
    {
        $sources =
            !is_array($objectOrClass) ?
                [$objectOrClass] :
                array_filter($objectOrClass, fn($el) => is_string($el) || is_object($el));

        return array_map(fn($class) => new ReflectionClass($class), $sources);
    }

    private function fromReflection(array $reflectors): Collection
    {
        $parsedAttributes = [];
        array_walk_recursive($reflectors, function ($reflector) use (&$parsedAttributes) {
            array_push($parsedAttributes, ...array_map(
                fn(ReflectionAttribute $attribute) => new ParsedAttribute($attribute, $reflector),
                $reflector->getAttributes($this->attribute, $this->flag)
            ));
        });

        return new Collection(...$parsedAttributes);
    }

    public function fromClass(string|object|array $objectOrClass): Collection
    {
        return $this->fromReflection($this->normalize($objectOrClass));
    }

    public function fromProperties(string|object|array $objectOrClass, string ...$property): Collection
    {
        return $this->fromReflection(
            array_map(
                fn(ReflectionClass $rc) =>
                empty($property) ?
                    $rc->getProperties() :
                    array_filter(
                        array_map(fn($p) => $rc->hasProperty($p) ? $rc->getProperty($p) : null, $property)
                    ),
                $this->normalize($objectOrClass)
            )
        );
    }

    public function fromMethods(string|object|array $objectOrClass, string ...$method): Collection
    {
        return $this->fromReflection(
            array_map(
                fn(ReflectionClass $rc) =>
                    empty($method) ?
                        $rc->getMethods() :
                        array_filter(
                            array_map(fn($m) => $rc->hasMethod($m) ? $rc->getMethod($m) : null, $method)
                        ),
                $this->normalize($objectOrClass)
            )
        );
    }

    public function fromClassConstants(string|object|array $objectOrClass, string ...$constant): Collection
    {
        return $this->fromReflection(
            array_map(
                fn(ReflectionClass $rc) =>
                empty($constant) ?
                    $rc->getReflectionConstants() :
                    array_filter(
                        array_map(fn($c) => $rc->hasConstant($c) ? $rc->getReflectionConstant($c) : null, $constant)
                    ),
                $this->normalize($objectOrClass)
            )
        );
    }

    public function fromParameters(string|object|array $objectOrClass, string $method, string ...$parameter): Collection
    {
        array_walk($parameter, 'mb_strtolower');
        return $this->fromReflection(
            array_map(
                fn(ReflectionClass $rc) =>
                empty($parameter) && $rc->hasMethod($method) ?
                    $rc->getMethod($method)->getParameters() :
                    array_filter(
                        $rc->hasMethod($method) ? $rc->getMethod($method)->getParameters() : [],
                        fn(ReflectionParameter $rp) => in_array(mb_strtolower($rp->getName()), $parameter)
                    ),
                $this->normalize($objectOrClass)
            )
        );
    }

    public function fromConstructor(string|object|array $objectOrClass): Collection
    {
        return $this->fromReflection(
            array_filter(
                array_map(
                    fn(ReflectionClass $c) => $c->getConstructor(),
                    $this->normalize($objectOrClass)
                )
            )
        );
    }

    public function fromConstructorParameters(string|object|array $objectOrClass, string ...$parameter): Collection
    {
        array_walk($parameter, 'mb_strtolower');
        return $this->fromReflection(
            array_map(
                fn(ReflectionClass $c) =>
                array_filter(
                    $c->getConstructor()?->getParameters() ?? [],
                    fn(ReflectionParameter $rp) =>
                        empty($parameter) || in_array(mb_strtolower($rp->getName()), $parameter)
                ),
                $this->normalize($objectOrClass)
            )
        );
    }
}
