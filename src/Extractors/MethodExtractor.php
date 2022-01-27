<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Modifiers\Modifier;
use BrenoRoosevelt\PhpAttributes\ParsedAttribtubeCollection;

use BrenoRoosevelt\PhpAttributes\Exception\ClassDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\MethodDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionMethod;

class MethodExtractor implements Extractor
{
    use ReflectionTrait;

    /** @var string[] */
    private readonly array $methods;
    private readonly ?int $filter;

    public function __construct(
        private readonly string|object $classOrObject,
        array $modifiers = [],
        string ...$methods
    ) {
        $this->methods = $methods;
        $this->filter = $this->sumModifiers(...array_filter($modifiers, fn($m) => $m instanceof Modifier));
    }

    /**
     * @inheritDoc
     * @throws ClassDoesNotExists if the class does not exist
     * @throws MethodDoesNotExists if the method does not exist
     */
    public function extract(string $attribute = null, int $flag = 0): ParsedAttribtubeCollection
    {
        $reflectionClass = $this->getClassOrFail($this->classOrObject);
        $reflectionMethods =
            empty($this->methods) ?
                $reflectionClass->getMethods($this->filter) :
                array_filter(
                    array_map(
                        fn(string $method) => $this->getMethodOrFail($reflectionClass, $method),
                        $this->methods
                    ),
                    fn(ReflectionMethod $rm) => $this->filterModifiers($rm)
                );

        return (new ReflectionExtractor(...$reflectionMethods))->extract($attribute, $flag);
    }

    private function filterModifiers(ReflectionMethod $reflectionMethod): bool
    {
        return ($this->filter === null) || ($this->filter & $reflectionMethod->getModifiers());
    }
}
