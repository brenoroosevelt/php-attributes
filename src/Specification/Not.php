<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Specification;

use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Specification;

class Not implements Specification
{
    /** @var Specification[] */
    private array $specifications;

    public function __construct(Specification ...$specifications)
    {
        $this->specifications = $specifications;
    }

    public function isSatisfiedBy(ParsedAttribute $attribute): bool
    {
        foreach ($this->specifications as $specification) {
            if ($specification->isSatisfiedBy($attribute)) {
                return false;
            }
        }

        return true;
    }
}
