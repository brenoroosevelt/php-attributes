<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionClass;
use ReflectionParameter;

class ConstructorParamsExtractor implements Extractor
{
    /** @var string[] */
    private readonly array $params;

    public function __construct(private readonly string|object $classOrObject, string ...$params)
    {
        array_walk($params, fn($p) => mb_strtolower($p));
        $this->params = $params;
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $reflectionClass = new ReflectionClass($this->classOrObject);
        $reflectionConstructorParams =
            empty($this->params) ?
                $reflectionClass->getConstructor()?->getParameters() ?? [] :
                array_filter(
                    $reflectionClass->getConstructor()?->getParameters() ?? [],
                    fn(ReflectionParameter $rp) => in_array(mb_strtolower($rp->getName()), $this->params)
                );

        return (new ReflectionExtractor($reflectionConstructorParams))->extract($attribute, $flag);
    }
}
