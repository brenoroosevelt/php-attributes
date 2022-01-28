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
            $types = $this->getTypeHint($target);
        } elseif ($target instanceof ReflectionFunctionAbstract) {
            $types = $this->getReturnType($target);
        }

        foreach ($this->types as $type) {
            if (in_array($type, $types)) {
                return true;
            }
        }

        return false;
    }

    private function getReturnType(ReflectionFunctionAbstract $function): array
    {
        $types = [];
        $returnType = $function->getReturnType();
        if ($returnType === null) {
            return $types;
        }

        $this->determineTypeOf($returnType, $types);
        $this->parseStaticTypes($function, $types);
        return $types;
    }

    private function getTypeHint(ReflectionProperty|ReflectionParameter $subject): array
    {
        $types = [];
        $typeHint = $subject->getType();
        if ($typeHint === null) {
            return $types;
        }

        $this->determineTypeOf($typeHint, $types);
        $this->parseStaticTypes($subject, $types);
        return $types;
    }

    private function determineTypeOf(ReflectionType $reflectionType, array &$types): void
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
