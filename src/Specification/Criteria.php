<?php
declare(strict_types=1);

namespace BrenoRosevelt\PhpAttributes\Specification;

use BrenoRosevelt\PhpAttributes\Specification;

class Criteria
{
    public static function and(Specification ...$specifications): Specification
    {
        return new AllOf(...$specifications);
    }

    public static function or(Specification ...$specifications): Specification
    {
        return new AnyOf(...$specifications);
    }

    public static function not(Specification ...$specifications): Specification
    {
        return new Not(...$specifications);
    }
}
