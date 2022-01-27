<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Modifiers\Modifier;
use BrenoRoosevelt\PhpAttributes\ParsedAttribtubeCollection;
use BrenoRoosevelt\PhpAttributes\Exception\ClassDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\PropertyDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionProperty;

class PropertyExtractor implements Extractor
{
    use ReflectionTrait;

    /** @var string[] */
    private readonly array $properties;
    private readonly ?int $filter;

    public function __construct(
        private readonly string|object $classOrObject,
        array $modifiers = [],
        string ...$properties
    ) {
        $this->properties = $properties;
        $this->filter = $this->sumModifiers(...array_filter($modifiers, fn($m) => $m instanceof Modifier));
    }

    /**
     * @inheritDoc
     * @throws ClassDoesNotExists if the class does not exist
     * @throws PropertyDoesNotExists if the property does not exist
     */
    public function extract(string $attribute = null, int $flag = 0): ParsedAttribtubeCollection
    {
        $reflectionClass = $this->getClassOrFail($this->classOrObject);
        $reflectionProperties =
            empty($this->properties) ?
                $reflectionClass->getProperties($this->filter) :
                array_filter(
                    array_map(
                        fn(string $property) => $this->getPropertyOrFail($reflectionClass, $property),
                        $this->properties
                    ),
                    fn(ReflectionProperty $rp) => $this->filterModifiers($rp)
                );

        return (new ReflectionExtractor(...$reflectionProperties))->extract($attribute, $flag);
    }

    private function filterModifiers(ReflectionProperty $reflectionProperty): bool
    {
        return ($this->filter === null) || ($this->filter & $reflectionProperty->getModifiers());
    }
}
