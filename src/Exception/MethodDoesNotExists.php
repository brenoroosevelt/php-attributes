<?php

namespace BrenoRoosevelt\PhpAttributes\Exception;

use Throwable;

class MethodDoesNotExists extends AttributeExtractionException
{
    public static function forMethod(
        string|object $objectOrClass,
        string $method,
        Throwable $previuos = null
    ): MethodDoesNotExists {
        return new MethodDoesNotExists(
            sprintf(
                'The method %s::%s does not exist.',
                $method,
                is_string($objectOrClass) ? $objectOrClass : get_class($objectOrClass)
            ),
            0,
            $previuos
        );
    }
}
