<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionClass;

class MethodExtractor implements Extractor
{
    /** @var string[] */
    private readonly array $methods;

    public function __construct(private readonly string|object $classOrObject, string ...$methods)
    {
        $this->methods = $methods;
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $reflectionClass = new ReflectionClass($this->classOrObject);
        $reflectionMethods =
            empty($this->methods) ?
                $reflectionClass->getProperties() :
                array_map(fn(string $method) => $reflectionClass->getMethod($method), $this->methods);

        return (new ReflectionExtractor($reflectionMethods))->extract($attribute, $flag);
    }
}
