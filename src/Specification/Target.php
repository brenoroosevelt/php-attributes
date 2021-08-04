<?php
declare(strict_types=1);

namespace BrenoRosevelt\PhpAttributes\Specification;

use BrenoRosevelt\PhpAttributes\ParsedAttribute;
use BrenoRosevelt\PhpAttributes\Specification;

class Target implements Specification
{
    public function __construct(private int $targert)
    {
    }

    public function isSatisfiedBy(ParsedAttribute $attribute): bool
    {
        return (bool) ($attribute->attribute()->getTarget() & $this->targert);
    }
}
