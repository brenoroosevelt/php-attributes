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

/**
 * Filtering capabilities
 * $collection->reject(new TargetMatchType('int'))
 * $collection->accept(new TargetMatchType('int', 'null', Foo::class))
 */
final class TargetMatchType
{
    /** @var string[] */
    private readonly array $desiredTypes;

    public function __construct(string ...$desiredTypes)
    {
        $this->desiredTypes = $desiredTypes;
    }

    public function __invoke(ParsedAttribute $parsedAttribute): bool
    {
        $target = $parsedAttribute->target;
        $targetTypes = [];

        if ($target instanceof ReflectionParameter || $target instanceof ReflectionProperty) {
            $this->determineTypeOf($target->getType(), $targetTypes);
        } elseif ($target instanceof ReflectionFunctionAbstract) {
            $this->determineTypeOf($target->getReturnType(), $targetTypes);
        }

        $this->parseStaticTypes($target, $targetTypes);
        foreach ($this->desiredTypes as $desiredType) {
            if (in_array($desiredType, $targetTypes)) {
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
