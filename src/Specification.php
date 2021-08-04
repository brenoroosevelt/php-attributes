<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

interface Specification
{
    public function isSatisfiedBy(ParsedAttribute $attribute): bool;
}
