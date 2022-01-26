<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use ReflectionAttribute;
use function array_map;
use function count;

class Collection implements IteratorAggregate, Countable
{
    /** @var ParsedAttribute[] */
    private readonly array $data;

    final public function __construct(ParsedAttribute ...$attributes)
    {
        $this->data = $attributes;
    }

    public function add(ParsedAttribute ...$attributes) :self
    {
        return new self(...$this->data, ...$attributes);
    }

    public function merge(Collection $collection) :self
    {
        return new self(...$this->data, ...$collection->data);
    }

    public function first(): ?ParsedAttribute
    {
        return $this->data[0] ?? null;
    }

    /** @return object[] */
    public function getInstances(): array
    {
        return array_map(fn(ParsedAttribute $attribute) => $attribute->attribute()->newInstance(), $this->data);
    }

    /** @return ReflectionAttribute[] */
    public function getAttributes(): array
    {
        return array_map(fn(ParsedAttribute $attribute) => $attribute->attribute(), $this->data);
    }

    public function getTargets(): array
    {
        return array_map(fn(ParsedAttribute $attribute) => $attribute->target(), $this->data);
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }

    /** @return ParsedAttribute[] */
    public function toArray(): array
    {
        return $this->data;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }
}
