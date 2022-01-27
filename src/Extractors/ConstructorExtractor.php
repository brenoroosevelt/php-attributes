<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\ParsedAttribtubeCollection;
use BrenoRoosevelt\PhpAttributes\Exception\ClassDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Extractor;

class ConstructorExtractor implements Extractor
{
    use ReflectionTrait;

    public function __construct(private readonly string|object $classOrObject)
    {
    }

    /**
     * @inheritDoc
     * @throws ClassDoesNotExists if the class does not exist
     */
    public function extract(string $attribute = null, int $flag = 0): ParsedAttribtubeCollection
    {
        $reflectionClass = $this->getClassOrFail($this->classOrObject);
        $reflectionConstructor = array_filter([$reflectionClass->getConstructor()]);
        return (new ReflectionExtractor(...$reflectionConstructor))->extract($attribute, $flag);
    }
}
