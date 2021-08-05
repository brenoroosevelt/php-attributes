<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests\Specification;

use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Specification;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Attr1;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Attr2;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Stub;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\StubInterface;
use BrenoRoosevelt\PhpAttributes\Tests\TestCase;
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

    public function testCriteria()
    {
        $not = Specification\Criteria::and($this->alwaysFalse(), $this->alwaysTrue());
        $this->assertFalse($not->isSatisfiedBy($this->newParsedAttribute()));

        $not = Specification\Criteria::or($this->alwaysFalse(), $this->alwaysTrue());
        $this->assertTrue($not->isSatisfiedBy($this->newParsedAttribute()));

        $not = Specification\Criteria::not($this->alwaysTrue());
        $this->assertFalse($not->isSatisfiedBy($this->newParsedAttribute()));
    }

    public function testAttributeName()
    {
        $spec = new Specification\AttributeName(Attr1::class);
        $this->assertTrue($spec->isSatisfiedBy($this->newParsedAttribute()));
        $spec = new Specification\AttributeName(Attr2::class);
        $this->assertFalse($spec->isSatisfiedBy($this->newParsedAttribute()));
    }

    public function testAttributeTarget()
    {
        $spec = new Specification\AttributeTarget(\Attribute::TARGET_CLASS);
        $this->assertTrue($spec->isSatisfiedBy($this->newParsedAttribute()));
        $spec = new Specification\AttributeTarget(\Attribute::TARGET_METHOD);
        $this->assertFalse($spec->isSatisfiedBy($this->newParsedAttribute()));
    }

    public function testMethodName()
    {
        $target = (new ReflectionClass(Stub::class))->getMethod('foo');
        $attribute = $target->getAttributes(Attr1::class)[0];
        $parsedAttribute = new ParsedAttribute($attribute, $target);

        $spec = new Specification\MethodName('foo');
        $this->assertTrue($spec->isSatisfiedBy($parsedAttribute));
        $spec = new Specification\MethodName('bar');
        $this->assertFalse($spec->isSatisfiedBy($parsedAttribute));
    }

    public function testTargetMatchTypeProperty()
    {
        $target = (new ReflectionClass(Stub::class))->getProperty('x');
        $attribute = $target->getAttributes(Attr1::class)[0];
        $parsedAttribute = new ParsedAttribute($attribute, $target);

        $spec = new Specification\TargetMatchType(Stub::class);

        $this->assertTrue($spec->isSatisfiedBy($parsedAttribute));

        $spec = new Specification\TargetMatchType('float');
        $this->assertFalse($spec->isSatisfiedBy($parsedAttribute));

        $spec = new Specification\TargetMatchType('float');
        $this->assertFalse($spec->isSatisfiedBy($this->newParsedAttribute()));
    }

    public function testTargetParameterTypeHint()
    {
        $target = (new ReflectionClass(Stub::class))->getMethod('foo')->getParameters()[0];
        $attribute = $target->getAttributes(Attr1::class)[0];
        $parsedAttribute = new ParsedAttribute($attribute, $target);

        $spec = new Specification\TargetIsSubclassOf(StubInterface::class);
        $this->assertFalse($spec->isSatisfiedBy($parsedAttribute));

        $spec = new Specification\TargetMatchType('string');
        $this->assertTrue($spec->isSatisfiedBy($parsedAttribute));
    }

    public function testTargetIsSubClassOf()
    {
        $target = (new ReflectionClass(Stub::class))->getMethod('foo');
        $attribute = $target->getAttributes(Attr1::class)[0];
        $parsedAttribute = new ParsedAttribute($attribute, $target);

        $spec = new Specification\TargetIsSubclassOf(StubInterface::class);

        $this->assertTrue($spec->isSatisfiedBy($parsedAttribute));

        $spec = new Specification\TargetIsSubclassOf('string');
        $this->assertFalse($spec->isSatisfiedBy($parsedAttribute));
    }
}
