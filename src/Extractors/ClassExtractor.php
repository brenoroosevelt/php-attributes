<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\ParsedAttribtubeCollection;
use BrenoRoosevelt\PhpAttributes\Exception\ClassDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Extractor;

class ClassExtractor implements Extractor
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
        return (new ReflectionExtractor($this->getClassOrFail($this->classOrObject)))->extract($attribute, $flag);
    }
}
