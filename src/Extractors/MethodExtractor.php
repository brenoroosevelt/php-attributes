<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Filtering\Modifier;
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
    private readonly ?int $modifiers;

    public function __construct(
        private readonly string|object $classOrObject,
        Modifier|string ...$modifiersAndOrMethods
    ) {
        $this->methods = array_filter($modifiersAndOrMethods, 'is_string');
        $this->modifiers = Modifier::sum(...array_filter($modifiersAndOrMethods, fn($m) => $m instanceof Modifier));
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
                $reflectionClass->getMethods($this->modifiers) :
                array_filter(
                    array_map(
                        fn(string $method) => $this->getMethodOrFail($reflectionClass, $method),
                        $this->methods
                    ),
                    fn(ReflectionMethod $rm) => $this->filterModifiers($rm, $this->modifiers)
                );

        return (new ReflectionExtractor(...$reflectionMethods))->extract($attribute, $flag);
    }
}
