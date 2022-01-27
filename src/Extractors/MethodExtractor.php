<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionClass;
use ReflectionMethod;

class MethodExtractor implements Extractor
{
    /** @var string[] */
    private readonly array $methods;

    public function __construct(
        private readonly string|object $classOrObject,
        private readonly ?int $filter = null,
        string ...$methods
    ) {
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
                $reflectionClass->getMethods($this->filter) :
                array_map(fn(string $method) => $reflectionClass->getMethod($method), $this->methods);

        $reflectionMethods = array_filter($reflectionMethods, fn($rm) => $this->filterModifiers($rm));

        return (new ReflectionExtractor($reflectionMethods))->extract($attribute, $flag);
    }

    private function filterModifiers(ReflectionMethod $reflectionMethod): bool
    {
        return ($this->filter === null) || ($this->filter & $reflectionMethod->getModifiers());
    }
}
