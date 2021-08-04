<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Specification;

use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Specification;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

class TypeIsSubclassOf implements Specification
{
    public function __construct(private string $class)
    {
    }

    public function isSatisfiedBy(ParsedAttribute $attribute): bool
    {
        $target = $attribute->target();
        if ($target instanceof ReflectionMethod ||
            $target instanceof ReflectionParameter ||
            $target instanceof ReflectionProperty
        ) {
            $types = Reflector::getTypeHint($target, false);
            foreach ($types as $type) {
                if (is_subclass_of($type, $this->class, true)) {
                    return true;
                }
            }
        }

        return false;
    }
}
