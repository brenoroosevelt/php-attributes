<?php

namespace BrenoRoosevelt\PhpAttributes\Exception;

use Throwable;

class ClassDoesNotExists extends AttributeExtractionException
{
    public static function forObjecOrClass(
        string|object $objectOrClass,
        Throwable $previuos = null
    ): ClassDoesNotExists {
        return new ClassDoesNotExists(
            sprintf(
                'The class %s does not exist.',
                is_string($objectOrClass) ? $objectOrClass : get_class($objectOrClass)
            ),
            0,
            $previuos
        );
    }
}
