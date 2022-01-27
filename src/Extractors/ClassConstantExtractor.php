<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionClass;
use ReflectionClassConstant;

class ClassConstantExtractor implements Extractor
{
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
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $reflectionClass = new ReflectionClass($this->classOrObject);
        $reflectionClassConstants =
            empty($this->constants) ?
                $reflectionClass->getReflectionConstants($this->filter) :
                array_filter(
                    array_map(
                        fn(string $constant) => $reflectionClass->getReflectionConstant($constant),
                        $this->constants
                    ),
                    fn(ReflectionClassConstant $rcc) => $this->filterModifiers($rcc)
                );

        return (new ReflectionExtractor($reflectionClassConstants))->extract($attribute, $flag);
    }

    private function filterModifiers(ReflectionClassConstant $classConstant): bool
    {
        return ($this->filter === null) || ($this->filter & $classConstant->getModifiers());
    }
}
