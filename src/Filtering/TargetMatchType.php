<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Filtering;

use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionNamedType as NamedType;
use ReflectionIntersectionType as IntersectionType;
use ReflectionType;
use ReflectionUnionType as UnionType;
use ReflectionParameter;
use ReflectionProperty;

final class TargetMatchType
{
    /** @var string[] */
    private readonly array $types;

    public function __construct(string ...$types)
    {
        $this->types = $types;
    }

    public function __invoke(ParsedAttribute $parsedAttribute): bool
    {
        $target = $parsedAttribute->target;
        $types = [];

        if ($target instanceof ReflectionParameter || $target instanceof ReflectionProperty) {
            $this->determineTypeOf($target->getType(), $types);
        } elseif ($target instanceof ReflectionFunctionAbstract) {
            $this->determineTypeOf($target->getReturnType(), $types);
        }

        $this->parseStaticTypes($target, $types);
        foreach ($this->types as $type) {
            if (in_array($type, $types)) {
                return true;
            }
        }

        return false;
    }

    private function determineTypeOf(?ReflectionType $reflectionType, array &$types): void
    {
        if ($reflectionType instanceof NamedType) {
            $types[] = $reflectionType->getName();
            if ($reflectionType->allowsNull()) {
                $types[] = 'null';
            }
        } elseif ($reflectionType instanceof UnionType || $reflectionType instanceof IntersectionType) {
            foreach ($reflectionType->getTypes() as $type) {
                $this->determineTypeOf($type, $types);
            }
        }
    }

    private function parseStaticTypes($subject, array &$types): void
    {
        if (in_array('self', $types) || in_array('static', $types)) {
            if ($subject instanceof ReflectionMethod || $subject instanceof ReflectionProperty) {
                $types[] = $subject->getDeclaringClass()->getName();
            } elseif ($subject instanceof ReflectionParameter && ($class = $subject->getDeclaringClass()) !== null) {
                $types[] = $class->getName();
            }
        }
    }
}
