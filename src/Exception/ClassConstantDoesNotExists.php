<?php

namespace BrenoRoosevelt\PhpAttributes\Exception;

use Throwable;

class ClassConstantDoesNotExists extends AttributeExtractionException
{
    public static function forClassConstant(
        string|object $objectOrClass,
        string $constant,
        Throwable $previuos = null
    ): ClassConstantDoesNotExists {
        return new ClassConstantDoesNotExists(
            sprintf(
                'The constant %s::%s does not exist.',
                $constant,
                is_string($objectOrClass) ? $objectOrClass : get_class($objectOrClass)
            ),
            0,
            $previuos
        );
    }
}
