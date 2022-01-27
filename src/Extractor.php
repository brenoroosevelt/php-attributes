<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

interface Extractor
{
    public function extract(string $attribute = null, int $flag = 0): Collection;
}
