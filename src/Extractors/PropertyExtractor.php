<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionClass;
use ReflectionProperty;

class PropertyExtractor implements Extractor
{
    /** @var string[] */
    private readonly array $properties;

    public function __construct(
        private readonly string|object $classOrObject,
        private readonly ?int $filter = null,
        string ...$properties
    ) {
        $this->properties = $properties;
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $reflectionClass = new ReflectionClass($this->classOrObject);
        $reflectionProperties =
            empty($this->properties) ?
                $reflectionClass->getProperties($this->filter) :
                array_filter(
                    array_map(
                        fn(string $property) => $reflectionClass->getProperty($property),
                        $this->properties
                    ),
                    fn(ReflectionProperty $rp) => $this->filterModifiers($rp)
                );

        return (new ReflectionExtractor($reflectionProperties))->extract($attribute, $flag);
    }

    private function filterModifiers(ReflectionProperty $reflectionProperty): bool
    {
        return ($this->filter === null) || ($this->filter & $reflectionProperty->getModifiers());
    }
}
