<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Specification;

use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionUnionType;

class Reflector
{
    public static function getTypeHint(
        ReflectionParameter|ReflectionMethod|ReflectionProperty $subject,
        bool $detectPrimitiveTypes = true
    ): array {
        $type = $subject instanceof ReflectionMethod ? $subject->getReturnType() : $subject->getType();
        $types = [];
        $result = [];

        if ($type instanceof ReflectionNamedType) {
            $types[] = $type;
        } elseif ($type instanceof ReflectionUnionType) {
            $types = $type->getTypes();
        }

        foreach ($types as $type) {
            if ($type->isBuiltin() && !$detectPrimitiveTypes) {
                continue;
            }

            $typeHint = ltrim($type->getName(), "?");
            if ($typeHint === 'self') {
                $typeHint = $subject->getDeclaringClass()->getName();
            }

            $result[] = $typeHint;
        }

        return $result;
    }
}
