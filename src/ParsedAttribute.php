<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

// @codingStandardsIgnoreFile
final class ParsedAttribute
{
    public function __construct(
        private ReflectionAttribute $attribute,
        private ReflectionClass|ReflectionClassConstant|ReflectionProperty|ReflectionMethod|ReflectionParameter $target
    ) {
    }

    public function attribute(): ReflectionAttribute
    {
        return $this->attribute;
    }

    public function target(): ReflectionClassConstant|ReflectionParameter|ReflectionMethod|ReflectionClass|ReflectionProperty
    {
        return $this->target;
    }
}
