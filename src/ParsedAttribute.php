<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

final class ParsedAttribute
{
    public function __construct(
        public readonly ReflectionAttribute $attribute,
        public readonly
        ReflectionClass|
        ReflectionClassConstant|
        ReflectionProperty|
        ReflectionMethod|
        ReflectionParameter $target
    ) {
    }
}
