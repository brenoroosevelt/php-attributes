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

    public static function fromReflections(array $reflections, string $attribute = null, int $flag = 0): Collection
    {
        return (new ReflectionExtractor($reflections))->extract($attribute, $flag);
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $attributes = [];
        $reflections = $this->reflections;
        array_walk_recursive($reflections, function ($reflection) use (&$attributes, $attribute, $flag) {
            if ($reflection instanceof ReflectionClass ||
                $reflection instanceof ReflectionProperty ||
                $reflection instanceof ReflectionParameter ||
                $reflection instanceof ReflectionClassConstant ||
                $reflection instanceof ReflectionMethod ||
                $reflection instanceof ReflectionFunction
            ) {
                array_push($attributes, ...array_map(
                    fn(ReflectionAttribute $attribute) => new ParsedAttribute($attribute, $reflection),
                    $reflection->getAttributes($attribute, $flag)
                ));
            }
        });

        return new Collection(...$attributes);
    }
}
