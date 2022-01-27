<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionClass;
use ReflectionParameter;

class MethodParamsExtractor implements Extractor
{
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
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $reflectionMethod = (new ReflectionClass($this->classOrObject))->getMethod($this->method);
        $reflectionParams =
            empty($this->params) ?
                $reflectionMethod->getParameters() :
                array_filter(
                    $reflectionMethod->getParameters(),
                    fn(ReflectionParameter $rp) => in_array(mb_strtolower($rp->getName()), $this->params)
                );

        return (new ReflectionExtractor($reflectionParams))->extract($attribute, $flag);
    }
}
