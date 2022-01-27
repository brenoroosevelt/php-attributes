<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionClass;

class ClassConstantExtractor implements Extractor
{
    /** @var string[] */
    private readonly array $constants;

    public function __construct(private readonly string|object $classOrObject, string ...$constants)
    {
        $this->constants = $constants;
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $reflectionClass = new ReflectionClass($this->classOrObject);
        $reflectionClassConstants =
            empty($this->constants) ?
                $reflectionClass->getReflectionConstants() :
                array_map(fn(string $constant) => $reflectionClass->getReflectionConstant($constant), $this->constants);

        return (new ReflectionExtractor($reflectionClassConstants))->extract($attribute, $flag);
    }
}
