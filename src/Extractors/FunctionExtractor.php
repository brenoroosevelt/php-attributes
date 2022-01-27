<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\ParsedAttribtubeCollection;
use BrenoRoosevelt\PhpAttributes\Exception\FunctionDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Extractor;
use Closure;

class FunctionExtractor implements Extractor
{
    use ReflectionTrait;

    public function __construct(private readonly string|Closure $function)
    {
    }

    /**
     * @inheritDoc
     * @throws FunctionDoesNotExists if the function does not exist
     */
    public function extract(string $attribute = null, int $flag = 0): ParsedAttribtubeCollection
    {
        return (new ReflectionExtractor($this->getFunctionOrFail($this->function)))->extract($attribute, $flag);
    }
}
