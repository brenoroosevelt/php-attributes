<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Specification;

use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Specification;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

class TargetMatchType implements Specification
{
    public function __construct(private string $type)
    {
    }

    public function isSatisfiedBy(ParsedAttribute $attribute): bool
    {
        $target = $attribute->target();
        if ($target instanceof ReflectionMethod ||
            $target instanceof ReflectionParameter ||
            $target instanceof ReflectionProperty
        ) {
            $types = Reflector::getTypeHint($target);
            return in_array($this->type, $types);
        }

        return false;
    }
}
