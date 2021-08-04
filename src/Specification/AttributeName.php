<?php
declare(strict_types=1);

namespace BrenoRosevelt\PhpAttributes\Specification;

use BrenoRosevelt\PhpAttributes\ParsedAttribute;
use BrenoRosevelt\PhpAttributes\Specification;

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
