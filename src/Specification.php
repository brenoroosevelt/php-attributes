<?php
declare(strict_types=1);

namespace BrenoRosevelt\PhpAttributes;

interface Specification
{
    public function isSatisfiedBy(ParsedAttribute $attribute): bool;
}
