<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\ParsedAttribtubeCollection;
use BrenoRoosevelt\PhpAttributes\Exception\FunctionDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Extractor;
use Closure;
use ReflectionParameter;

class FunctionParamsExtractor implements Extractor
{
    use ReflectionTrait;

    private readonly array $params;

    public function __construct(private readonly string|Closure $function, string ...$params)
    {
        array_walk($params, fn($p) => mb_strtolower($p));
        $this->params = $params;
    }

    /**
     * @inheritDoc
     * @throws FunctionDoesNotExists if the function does not exist
     */
    public function extract(string $attribute = null, int $flag = 0): ParsedAttribtubeCollection
    {
        $reflectionFunction = $this->getFunctionOrFail($this->function);
        $reflectionParams =
            empty($this->params) ?
                $reflectionFunction->getParameters() :
                array_filter(
                    $reflectionFunction->getParameters(),
                    fn(ReflectionParameter $rp) => in_array(mb_strtolower($rp->getName()), $this->params)
                );

        return (new ReflectionExtractor(...$reflectionParams))->extract($attribute, $flag);
    }
}
