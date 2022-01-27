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
    private readonly array $reflections;

    public function __construct(
        ReflectionClass |
        ReflectionProperty |
        ReflectionParameter |
        ReflectionMethod |
        ReflectionClassConstant |
        ReflectionFunction ...$reflections
    ) {
        $this->reflections = $reflections;
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $parsedAttributes = [];
        foreach ($this->reflections as $reflection) {
            array_push($parsedAttributes, ...array_map(
                fn(ReflectionAttribute $attribute) => new ParsedAttribute($attribute, $reflection),
                $reflection->getAttributes($attribute, $flag)
            ));
        }

        return new Collection(...$parsedAttributes);
    }
}
