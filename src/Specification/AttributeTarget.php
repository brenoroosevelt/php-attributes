<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Specification;

use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Specification;

class AttributeTarget implements Specification
{
    public function __construct(private int $target)
    {
    }

    public function isSatisfiedBy(ParsedAttribute $attribute): bool
    {
        return (bool) ($attribute->attribute()->getTarget() & $this->target);
    }
}
