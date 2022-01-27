<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use Attribute;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

final class ParsedAttribute
{
    public function __construct(
        public readonly ReflectionAttribute $attribute,
        public readonly
        ReflectionClass |
        ReflectionClassConstant |
        ReflectionProperty |
        ReflectionMethod |
        ReflectionFunction |
        ReflectionParameter $target
    ) {
    }
}
