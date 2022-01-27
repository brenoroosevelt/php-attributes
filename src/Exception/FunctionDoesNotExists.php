<?php

namespace BrenoRoosevelt\PhpAttributes\Exception;

use Closure;
use Throwable;

class FunctionDoesNotExists extends AttributeExtractionException
{
    public static function forFunction(
        string|Closure $function,
        Throwable $previuos = null
    ): FunctionDoesNotExists {
        return new FunctionDoesNotExists(
            sprintf(
                'The function %s does not exist.',
                is_string($function) ? $function : gettype($function)
            ),
            0,
            $previuos
        );
    }
}
