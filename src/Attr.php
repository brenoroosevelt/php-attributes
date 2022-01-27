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
use BrenoRoosevelt\PhpAttributes\Modifiers\Modifier;
use Closure;

/**
 * Helper class for attribute extraction
 */
final class Attr
{
    /**
     * @param string|object|array $objectOrClass
     * @return Extractor
     */
    public static function fromClass(string|object|array $objectOrClass): Extractor
    {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new ClassExtractor($objectOrClass),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    /**
     * @param string|object|array $objectOrClass
     * @param Modifier[] $filter
     * @param string ...$properties
     * @return Extractor
     */
    public static function fromProperties(
        string|object|array $objectOrClass,
        array $filter = [],
        string ...$properties
    ): Extractor {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new PropertyExtractor($objectOrClass, $filter, ...$properties),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    /**
     * @param string|object|array $objectOrClass
     * @param Modifier[] $filter
     * @param string ...$methods
     * @return Extractor
     */
    public static function fromMethods(
        string|object|array $objectOrClass,
        array $filter = [],
        string ...$methods
    ): Extractor {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new MethodExtractor($objectOrClass, $filter, ...$methods),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    /**
     * @param string|object|array $objectOrClass
     * @param string $method
     * @param string ...$params
     * @return Extractor
     */
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

    /**
     * @param string|object|array $objectOrClass
     * @param Modifier[] $filter
     * @param string ...$constants
     * @return Extractor
     */
    public static function fromClassConstants(
        string|object|array $objectOrClass,
        array $filter = [],
        string ...$constants
    ): Extractor {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new ClassConstantExtractor($objectOrClass, $filter, ...$constants),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    /**
     * @param string|object|array $objectOrClass
     * @return Extractor
     */
    public static function fromConstructor(string|object|array $objectOrClass): Extractor
    {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new ConstructorExtractor($objectOrClass),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    /**
     * @param string|object|array $objectOrClass
     * @param string ...$params
     * @return Extractor
     */
    public static function fromConstructorParams(string|object|array $objectOrClass, string ...$params): Extractor
    {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new ConstructorParamsExtractor($objectOrClass, ...$params),
            is_array($objectOrClass) ? $objectOrClass : [$objectOrClass]
        );

        return new CompositeExtractor(...$extractors);
    }

    /**
     * @param string|Closure|array $fn
     * @return Extractor
     */
    public static function fromFunctions(string|Closure|array $fn): Extractor
    {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new FunctionExtractor($fn),
            is_array($fn) ? $fn : [$fn]
        );

        return new CompositeExtractor(...$extractors);
    }

    /**
     * @param string|Closure|array $fn
     * @param string ...$params
     * @return Extractor
     */
    public static function fromFunctionParams(string|Closure|array $fn, string ...$params): Extractor
    {
        $extractors = array_map(
            fn(string|object $objectOrClass) => new FunctionParamsExtractor($fn, ...$params),
            is_array($fn) ? $fn : [$fn]
        );

        return new CompositeExtractor(...$extractors);
    }
}
