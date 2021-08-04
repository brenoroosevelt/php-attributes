<?php
declare(strict_types=1);

namespace BrenoRosevelt\PhpAttributes;

use ArrayIterator;
use BrenoRosevelt\PhpAttributes\Specification\Attribute;
use BrenoRosevelt\PhpAttributes\Specification\Target;
use Countable;
use IteratorAggregate;
use ReflectionAttribute;
use function array_filter;
use function array_map;
use function count;

class Attributes extends AttributesFactory implements IteratorAggregate, Countable
{
    /** @var ParsedAttribute[] */
    private array $attributes;

    final public function __construct(ParsedAttribute ...$attributes)
    {
        $this->attributes = $attributes;
    }

    public function filter(callable $fn): self
    {
        return new self(...array_filter($this->attributes, $fn));
    }

    public function where(Specification $specification): self
    {
        return $this->filter(fn(ParsedAttribute $attribute) => $specification->isSatisfiedBy($attribute));
    }

    public function whereName(string $attributeName): self
    {
        return $this->where(new Attribute($attributeName));
    }

    public function whereTarget(int $target): self
    {
        return $this->where(new Target($target));
    }

    public function first(): self
    {
        return $this->isEmpty() ? new self() : new self($this->attributes[0]);
    }

    public function isEmpty(): bool
    {
        return empty($this->attributes);
    }

    public function instances(): array
    {
        return array_map(
            fn(ParsedAttribute $attribute) => $attribute->attribute()->newInstance(),
            $this->attributes
        );
    }

    public function singleInstance(mixed $default = null): mixed
    {
        return $this->first()->instances()[0] ?? $default;
    }

    /**
     * @return ReflectionAttribute[]
     */
    public function attributes(): array
    {
        return array_map(
            fn(ParsedAttribute $attribute) => $attribute->attribute(),
            $this->attributes
        );
    }

    public function targets(): array
    {
        return array_map(
            fn(ParsedAttribute $attribute) => $attribute->target(),
            $this->attributes
        );
    }

    public function has(string $attributeName): bool
    {
        return $this->whereName($attributeName)->count() > 0;
    }

    public function hasMany(string $attributeName): bool
    {
        return $this->whereName($attributeName)->count() > 1;
    }

    public function count(): int
    {
        return count($this->attributes);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->attributes);
    }
}
