<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;
use Closure;
use ReflectionFunction;

class FunctionExtractor implements Extractor
{
    public function __construct(private readonly string|Closure $function)
    {
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        return (new ReflectionExtractor(new ReflectionFunction($this->function)))->extract($attribute, $flag);
    }
}
