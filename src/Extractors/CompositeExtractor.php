<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\Extractor;

class CompositeExtractor implements Extractor
{
    private readonly array $extractors;

    public function __construct(Extractor ...$extractors)
    {
        $this->extractors = $extractors;
    }

    /**
     * @inheritDoc
     */
    public function extract(string $attribute = null, int $flag = 0): Collection
    {
        $collection = new Collection();
        foreach ($this->extractors as $extractor) {
            $collection = $collection->merge($extractor->extract($attribute, $flag));
        }

        return $collection;
    }
}
