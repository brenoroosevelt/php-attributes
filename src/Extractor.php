<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

interface Extractor
{
    /**
     * Returns a collection of `ParsedAttribute`
     *
     * @param string|null $attribute
     * @param int $flag
     * @return Collection
     */
    public function extract(string $attribute = null, int $flag = 0): Collection;
}
