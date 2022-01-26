<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use ReflectionAttribute;
use Traversable;

class Collectionaaa implements IteratorAggregate, Countable
{
    /** @var ParsedAttribute[] */
    private readonly array $data;

    final public function __construct(ParsedAttribute ...$parsedAttribute)
    {
        $this->data = $parsedAttribute;
    }

    public function add(ParsedAttribute ...$parsedAttribute): self
    {
        return new self(...$this->data, ...$parsedAttribute);
    }

    public function merge(Collectionaaa $collection): self
    {
        return new self(...$this->data, ...$collection->data);
    }

    /** @return ReflectionAttribute[] */
    public function attributes(): array
    {
        return array_map(fn(ParsedAttribute $attribute) => $attribute->attribute(), $this->data);
    }

    /** @return object[] */
    public function instances(): array
    {
        return array_map(fn(ParsedAttribute $attribute) => $attribute->attribute()->newInstance(), $this->data);
    }

    public function targets(): array
    {
        return array_map(fn(ParsedAttribute $attribute) => $attribute->target(), $this->data);
    }

    public function first(): ?ParsedAttribute
    {
        return $this->data[0] ?? null;
    }

    public function isEmpty(): bool
    {
        return count($this->data) === 0;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }
}
