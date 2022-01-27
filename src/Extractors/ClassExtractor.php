<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionClass;

class ClassExtractor implements Extractor
{
    public function __construct(private readonly string|object $classOrObject)
    {
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        return (new ReflectionExtractor(new ReflectionClass($this->classOrObject)))->extract($attribute, $flag);
    }
}
