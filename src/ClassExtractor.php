<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use ReflectionAttribute;
use ReflectionClass;

class ClassExtractor implements Extractor
{
    public function __construct(private readonly string|object $classOrObject)
    {
    }

    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $reflectionClass = new ReflectionClass($this->classOrObject);
        return new Collection(
            ...array_map(
                fn(ReflectionAttribute $ra) => new ParsedAttribute($ra, $reflectionClass),
                $reflectionClass->getAttributes($attribute, $flag)
            )
        );
    }
}
