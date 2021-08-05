<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests\Specification;


use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Specification;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Attr1;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Stub;
use BrenoRoosevelt\PhpAttributes\Tests\TestCase;
use FlexFqcnFinder\Filter\Specifications\AlwaysTrue;
use ReflectionClass;

class SpecificationTest extends TestCase
{
    private function alwaysTrue(): Specification
    {
        return new class implements Specification {
            public function isSatisfiedBy(ParsedAttribute $attribute): bool
            {
                return true;
            }
        };
    }

    private function alwaysFalse(): Specification
    {
        return new class implements Specification {
            public function isSatisfiedBy(ParsedAttribute $attribute): bool
            {
                return false;
            }
        };
    }

    private function newParsedAttribute(): ParsedAttribute
    {
        $target = new ReflectionClass(Stub::class);
        $attribute = $target->getAttributes(Attr1::class)[0];
        return new ParsedAttribute($attribute, $target);
    }

    public function testAllOf()
    {
        $allOf = new Specification\AllOf($this->alwaysFalse(), $this->alwaysTrue());
        $this->assertFalse($allOf->isSatisfiedBy($this->newParsedAttribute()));

        $allOf = new Specification\AllOf($this->alwaysFalse(), $this->alwaysFalse());
        $this->assertFalse($allOf->isSatisfiedBy($this->newParsedAttribute()));

        $allOf = new Specification\AllOf($this->alwaysTrue(), $this->alwaysTrue());
        $this->assertTrue($allOf->isSatisfiedBy($this->newParsedAttribute()));
    }

    public function testAnyOf()
    {
        $anyOf = new Specification\AnyOf($this->alwaysFalse(), $this->alwaysTrue());
        $this->assertTrue($anyOf->isSatisfiedBy($this->newParsedAttribute()));

        $anyOf = new Specification\AnyOf($this->alwaysFalse(), $this->alwaysFalse());
        $this->assertFalse($anyOf->isSatisfiedBy($this->newParsedAttribute()));

        $anyOf = new Specification\AnyOf($this->alwaysTrue(), $this->alwaysTrue());
        $this->assertTrue($anyOf->isSatisfiedBy($this->newParsedAttribute()));
    }

    public function testNot()
    {
        $not = new Specification\Not($this->alwaysFalse(), $this->alwaysTrue());
        $this->assertFalse($not->isSatisfiedBy($this->newParsedAttribute()));

        $not = new Specification\Not($this->alwaysFalse(), $this->alwaysFalse());
        $this->assertTrue($not->isSatisfiedBy($this->newParsedAttribute()));

        $not = new Specification\Not($this->alwaysTrue());
        $this->assertFalse($not->isSatisfiedBy($this->newParsedAttribute()));

        $not = new Specification\Not($this->alwaysFalse());
        $this->assertTrue($not->isSatisfiedBy($this->newParsedAttribute()));
    }
}