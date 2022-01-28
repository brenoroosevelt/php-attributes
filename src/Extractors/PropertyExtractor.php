<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Filtering\Modifier;
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
    private readonly ?int $modifiers;

    public function __construct(
        private readonly string|object $classOrObject,
        Modifier|string ...$modifiersAndOrProperties,
    ) {
        $this->properties = array_filter($modifiersAndOrProperties, 'is_string');
        $this->modifiers = Modifier::sum(...array_filter($modifiersAndOrProperties, fn($m) => $m instanceof Modifier));
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
                $reflectionClass->getProperties($this->modifiers) :
                array_filter(
                    array_map(
                        fn(string $property) => $this->getPropertyOrFail($reflectionClass, $property),
                        $this->properties
                    ),
                    fn(ReflectionProperty $rp) => $this->filterModifiers($rp, $this->modifiers)
                );

        return (new ReflectionExtractor(...$reflectionProperties))->extract($attribute, $flag);
    }
}
