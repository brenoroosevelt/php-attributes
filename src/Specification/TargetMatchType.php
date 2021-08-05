<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Specification;

use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Specification;

class TargetMatchType implements Specification
{
    public function __construct(private string $type)
    {
    }

    public function isSatisfiedBy(ParsedAttribute $attribute): bool
    {
        $target = $attribute->target();
        return Reflector::matchType($target, $this->type);
    }
}
