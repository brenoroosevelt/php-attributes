<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Specification;

use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Specification;

class AttributeName implements Specification
{
    public function __construct(private string $name)
    {
    }

    public function isSatisfiedBy(ParsedAttribute $attribute): bool
    {
        return $attribute->attribute()->getName() === $this->name;
    }
}
