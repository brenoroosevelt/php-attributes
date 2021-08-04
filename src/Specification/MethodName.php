<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Specification;

use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Specification;
use ReflectionMethod;

class MethodName implements Specification
{
    public function __construct(private string $name)
    {
    }

    public function isSatisfiedBy(ParsedAttribute $attribute): bool
    {
        $target = $attribute->target();
        return $target instanceof ReflectionMethod && $target->getName() === $this->name;
    }
}
