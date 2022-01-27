<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

class ReflectionExtractor implements Extractor
{
    public function __construct(private readonly array $reflections)
    {
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $parsedAttributes = [];
        $reflections = $this->reflections;
        array_walk_recursive($reflections, function ($reflection) use (&$parsedAttributes, $attribute, $flag) {
            if ($this->isValidReflection($reflection)) {
                array_push($parsedAttributes, ...array_map(
                    fn(ReflectionAttribute $attribute) => new ParsedAttribute($attribute, $reflection),
                    $reflection->getAttributes($attribute, $flag)
                ));
            }
        });

        return new Collection(...$parsedAttributes);
    }

    private function isValidReflection($reflection): bool
    {
        return $reflection instanceof ReflectionClass
            || $reflection instanceof ReflectionProperty
            || $reflection instanceof ReflectionParameter
            || $reflection instanceof ReflectionClassConstant
            || $reflection instanceof ReflectionMethod
            || $reflection instanceof ReflectionFunction;
    }
}
