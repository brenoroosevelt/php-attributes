<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Specification;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionFunctionAbstract;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionUnionType;

class Reflector
{
    public static function getTypeHint(\Reflector $subject): array
    {
        if ($subject instanceof ReflectionClass) {
            $type = $subject->getName();
        } elseif ($subject instanceof ReflectionFunctionAbstract) {
            $type = $subject->getReturnType();
        } elseif ($subject instanceof ReflectionParameter) {
            $type = $subject->getType();
        } elseif ($subject instanceof ReflectionProperty) {
            $type = $subject->getType();
        } elseif ($subject instanceof ReflectionClassConstant) {
            return [gettype($subject->getValue())];
        } else {
            return [];
        }

        $types = [];
        $result = [];

        if ($type instanceof ReflectionNamedType) {
            $types[] = $type;
        } elseif ($type instanceof ReflectionUnionType) {
            $types = $type->getTypes();
        }

        foreach ($types as $type) {
            if ($type->allowsNull()) {
                $result[] = 'null';
            }

            $typeHint = ltrim($type->getName(), "?");
            if ($typeHint === 'self') {
                $typeHint = $subject->getDeclaringClass()->getName();
            }

            $result[] = $typeHint;
        }

        return $result;
    }

    public static function matchType(\Reflector $subject, string $type): bool
    {
        $types = Reflector::getTypeHint($subject);
        if (in_array($type, $types, true)) {
            return true;
        }

        foreach ($types as $typeHint) {
            if (is_a($typeHint, $type, true)) {
                return true;
            }
        }

        return false;
    }
}
