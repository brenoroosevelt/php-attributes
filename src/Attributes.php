<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes;

use ArrayIterator;
use Attribute;
use BrenoRoosevelt\PhpAttributes\Specification\AttributeName;
use BrenoRoosevelt\PhpAttributes\Specification\AttributeTarget;
use Countable;
use IteratorAggregate;
use ReflectionAttribute;
use function array_filter;
use function array_map;
use function count;

class Attributes implements IteratorAggregate, Countable
{
    /** @var ParsedAttribute[] */
    private array $attributes;

    final public function __construct(ParsedAttribute ...$attributes)
    {
        $this->attributes = $attributes;
    }

    public static function from(
        object|string|array $class,
        int $target = Attribute::TARGET_ALL,
        string $attribute = null,
        int $flags = 0
    ): Attributes {
        return (new AttributesFactory)->from($class, $target, $attribute, $flags);
    }

    public function filter(callable $fn): self
    {
        return new self(...array_filter($this->attributes, $fn));
    }

    public function merge(self $attributes) :self
    {
        $merged = array_merge($this->attributes, $attributes->attributes);
        return new self(...$merged);
    }

    public function add(ParsedAttribute ...$attributes) :self
    {
        $new = $this->attributes;
        foreach ($attributes as $attribute) {
            $new[] = $attribute;
        }

        return new self(...$new);
    }

    public function where(Specification $specification): self
    {
        return $this->filter(fn(ParsedAttribute $attribute) => $specification->isSatisfiedBy($attribute));
    }

    public function whereAttribute(string $attributeName): self
    {
        return $this->where(new AttributeName($attributeName));
    }

    public function whereTarget(int $target): self
    {
        return $this->where(new AttributeTarget($target));
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

    public function firstInstance(mixed $default = null): mixed
    {
        return isset($this->attributes[0]) ? $this->attributes[0]->attribute()->newInstance() : $default;
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

    public function hasAttribute(string $attributeName): bool
    {
        return $this->whereAttribute($attributeName)->count() > 0;
    }

    public function hasMany(string $attributeName): bool
    {
        return $this->whereAttribute($attributeName)->count() > 1;
    }

    public function hasTarget(int $target): bool
    {
        return $this->whereTarget($target)->count() > 0;
    }

    public function count(): int
    {
        return count($this->attributes);
    }

    /**
     * @return ParsedAttribute[]
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->attributes);
    }
}
