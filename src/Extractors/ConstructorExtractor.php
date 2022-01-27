<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionClass;

class ConstructorExtractor implements Extractor
{
    public function __construct(private readonly string|object $classOrObject)
    {
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $reflectionClass = new ReflectionClass($this->classOrObject);
        $reflectionConstructor = array_filter([$reflectionClass->getConstructor()]);
        return (new ReflectionExtractor(...$reflectionConstructor))->extract($attribute, $flag);
    }
}
