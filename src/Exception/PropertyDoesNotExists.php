<?php

namespace BrenoRoosevelt\PhpAttributes\Exception;

use Throwable;

class PropertyDoesNotExists extends AttributeExtractionException
{
    public static function forProperty(
        string|object $objectOrClass,
        string $property,
        Throwable $previuos = null
    ): PropertyDoesNotExists {
        return new PropertyDoesNotExists(
            sprintf(
                'The property %s::%s does not exist.',
                $property,
                is_string($objectOrClass) ? $objectOrClass : get_class($objectOrClass)
            ),
            0,
            $previuos
        );
    }
}
