<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use BrenoRoosevelt\PhpAttributes\Extractors\ClassConstantExtractor;
use BrenoRoosevelt\PhpAttributes\Extractors\ClassExtractor;
use BrenoRoosevelt\PhpAttributes\Extractors\CompositeExtractor;
use BrenoRoosevelt\PhpAttributes\Extractors\ConstructorExtractor;
use BrenoRoosevelt\PhpAttributes\Extractors\ConstructorParamsExtractor;
use BrenoRoosevelt\PhpAttributes\Extractors\FunctionExtractor;
use BrenoRoosevelt\PhpAttributes\Extractors\FunctionParamsExtractor;
use BrenoRoosevelt\PhpAttributes\Extractors\MethodExtractor;
use BrenoRoosevelt\PhpAttributes\Extractors\MethodParamsExtractor;
use BrenoRoosevelt\PhpAttributes\Extractors\PropertyExtractor;
use BrenoRoosevelt\PhpAttributes\Filtering\Modifier;
use Closure;

/**
 * Helper class for attribute extraction
 */
final class Attr
{
    public static function fromClass(string|object|array $objectOrClass): Extractor
    {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new ClassExtractor($objectOrClass),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    public static function fromProperties(
        string|object|array $objectOrClass,
        Modifier|string ...$modifiersAndOrProperties
    ): Extractor {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new PropertyExtractor($objectOrClass, ...$modifiersAndOrProperties),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    public static function fromMethods(
        string|object|array $objectOrClass,
        Modifier|string ...$modifiersAndOrMethods
    ): Extractor {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new MethodExtractor($objectOrClass, ...$modifiersAndOrMethods),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    public static function fromMethodParams(
        string|object|array $objectOrClass,
        string $method,
        string ...$params
    ): Extractor {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new MethodParamsExtractor($objectOrClass, $method, ...$params),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    public static function fromClassConstants(
        string|object|array $objectOrClass,
        Modifier|string ...$modifiersAndOrConstants
    ): Extractor {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new ClassConstantExtractor($objectOrClass, ...$modifiersAndOrConstants),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    public static function fromConstructor(string|object|array $objectOrClass): Extractor
    {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new ConstructorExtractor($objectOrClass),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    public static function fromConstructorParams(string|object|array $objectOrClass, string ...$params): Extractor
    {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new ConstructorParamsExtractor($objectOrClass, ...$params),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    public static function fromFunctions(string|Closure|array $fn): Extractor
    {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new FunctionExtractor($fn),
            is_array($fn) ? $fn : [$fn]
        );

        return new CompositeExtractor(...$extractors);
    }

    public static function fromFunctionParams(string|Closure|array $fn, string ...$params): Extractor
    {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new FunctionParamsExtractor($fn, ...$params),
            is_array($fn) ? $fn : [$fn]
        );

        return new CompositeExtractor(...$extractors);
    }
}
