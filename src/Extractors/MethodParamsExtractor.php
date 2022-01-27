<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\ParsedAttribtubeCollection;
use BrenoRoosevelt\PhpAttributes\Exception\ClassDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\MethodDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionParameter;

class MethodParamsExtractor implements Extractor
{
    use ReflectionTrait;

    /** @var string[] */
    private readonly array $params;

    public function __construct(
        private readonly string|object $classOrObject,
        private readonly string $method,
        string ...$params
    ) {
        array_walk($params, fn($p) => mb_strtolower($p));
        $this->params = $params;
    }

    /**
     * @inheritDoc
     * @throws ClassDoesNotExists if the class does not exist
     * @throws MethodDoesNotExists if the method does not exist
     */
    public function extract(string $attribute = null, int $flag = 0): ParsedAttribtubeCollection
    {
        $reflectionClass = $this->getClassOrFail($this->classOrObject);
        $reflectionMethod = $this->getMethodOrFail($reflectionClass, $this->method);
        $reflectionParams =
            empty($this->params) ?
                $reflectionMethod->getParameters() :
                array_filter(
                    $reflectionMethod->getParameters(),
                    fn(ReflectionParameter $rp) => in_array(mb_strtolower($rp->getName()), $this->params)
                );

        return (new ReflectionExtractor(...$reflectionParams))->extract($attribute, $flag);
    }
}
