<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\ParsedAttribtubeCollection;
use BrenoRoosevelt\PhpAttributes\Exception\ClassConstantDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\ClassDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionClassConstant;

class ClassConstantExtractor implements Extractor
{
    use ReflectionTrait;

    /** @var string[] */
    private readonly array $constants;

    public function __construct(
        private readonly string|object $classOrObject,
        private readonly ?int $filter = null,
        string ...$constants,
    ) {
        $this->constants = $constants;
    }

    /**
     * @inheritDoc
     * @throws ClassDoesNotExists if the class does not exist
     * @throws ClassConstantDoesNotExists if the constant does not exist
     */
    public function extract(string $attribute = null, int $flag = 0): ParsedAttribtubeCollection
    {
        $reflectionClass = $this->getClassOrFail($this->classOrObject);
        $reflectionClassConstants =
            empty($this->constants) ?
                $reflectionClass->getReflectionConstants($this->filter) :
                array_filter(
                    array_map(
                        fn(string $constant) => $this->getConstantOrFail($reflectionClass, $constant),
                        $this->constants
                    ),
                    fn(ReflectionClassConstant $rcc) => $this->filterModifiers($rcc)
                );

        return (new ReflectionExtractor(...$reflectionClassConstants))->extract($attribute, $flag);
    }

    private function filterModifiers(ReflectionClassConstant $classConstant): bool
    {
        return ($this->filter === null) || ($this->filter & $classConstant->getModifiers());
    }
}
