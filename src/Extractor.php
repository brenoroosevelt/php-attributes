<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use BrenoRoosevelt\PhpAttributes\Exception\AttributeExtractionException;

interface Extractor
{
    /**
     * Extracts attributes and returns them inside `ParsedAttribute` collection
     *
     * @param string|null $attribute the fully qualified class name for attribute
     * @param int $flag filter for attribute, ex: \ReflectionAttribute::IS_INSTANCEOF
     *
     * @return ParsedAttribtubeCollection a collection of `ParsedAttrbiutes`
     *
     * @throws AttributeExtractionException Error while extracting attribute
     * Usually when classes, methods, function or properties don't exist
     */
    public function extract(string $attribute = null, int $flag = 0): ParsedAttribtubeCollection;
}
