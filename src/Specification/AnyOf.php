<?php
declare(strict_types=1);

namespace BrenoRosevelt\PhpAttributes\Specification;

use BrenoRosevelt\PhpAttributes\ParsedAttribute;
use BrenoRosevelt\PhpAttributes\Specification;

class AnyOf implements Specification
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
                return true;
            }
        }

        return false;
    }
}
