<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use Closure;
use ReflectionFunction;
use ReflectionParameter;

class FunctionParamsExtractor implements Extractor
{
    private readonly array $params;

    public function __construct(private readonly string|Closure $function, string ...$params)
    {
        array_walk($params, fn($p) => mb_strtolower($p));
        $this->params = $params;
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $reflectionFunction = new ReflectionFunction($this->function);
        $reflectionParams =
            empty($this->params) ?
                $reflectionFunction->getParameters() :
                array_filter(
                    $reflectionFunction->getParameters(),
                    fn(ReflectionParameter $rp) => in_array(mb_strtolower($rp->getName()), $this->params)
                );

        return (new ReflectionExtractor($reflectionParams))->extract($attribute, $flag);
    }
}
