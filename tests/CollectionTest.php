<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests;

use Attribute;
use BrenoRoosevelt\PhpAttributes\Attributes;
use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Specification\AttributeTarget;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Attr1;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Attr2;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Stub;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionParameter;
use stdClass;

class CollectionTest extends TestCase
{
    /** @test */
    public function shouldFilterCollection()
    {
        $attributes = Attributes::from(Stub::class);

        $attributes = $attributes->filter(fn(ParsedAttribute $attr) => $attr->target() instanceof ReflectionClass);

        $this->assertEquals(2, $attributes->count());
        foreach ($attributes as $attribute) {
            $this->assertInstanceOf(ReflectionClass::class, $attribute->target());
        }
    }

    /** @test */
    public function shouldMergerCollection()
    {
        $attributes1 = Attributes::from(Stub::class, Attribute::TARGET_CLASS);
        $attributes2 = Attributes::from(Stub::class, Attribute::TARGET_PROPERTY);

        $attributes = $attributes1->merge($attributes2);

        $this->assertEquals(4, $attributes->count());
        $this->assertTrue($attributes->hasTarget(Attribute::TARGET_PROPERTY));
        $this->assertTrue($attributes->hasTarget(Attribute::TARGET_CLASS));
    }

    /** @test */
    public function shouldAddNewValue()
    {
        $attributes = Attributes::from(Stub::class, Attribute::TARGET_PROPERTY);
        $target = new ReflectionClass(Stub::class);
        $attribute = $target->getAttributes()[0];
        $parsedAttribute = new ParsedAttribute($attribute, $target);

        $attributes = $attributes->add($parsedAttribute);

        $this->assertEquals(3, $attributes->count());
        $this->assertTrue($attributes->hasTarget(Attribute::TARGET_PROPERTY));
        $this->assertTrue($attributes->hasTarget(Attribute::TARGET_CLASS));
    }

    /** @test */
    public function shouldFilterWithSpecification()
    {
        $attributes = Attributes::from(Stub::class);
        $attributes = $attributes->where(new AttributeTarget(Attribute::TARGET_PARAMETER));

        $this->assertEquals(2, $attributes->count());
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->targets());
    }

    /** @test */
    public function shouldFilterAttribute()
    {
        $attributes = Attributes::from(Stub::class);
        $attributes = $attributes->whereAttribute(Attr2::class);

        $this->assertContainsOnlyInstancesOf(Attr2::class, $attributes->instances());
    }

    /** @test */
    public function shouldFilterTarget()
    {
        $attributes = Attributes::from(Stub::class);
        $attributes = $attributes->whereTarget(Attribute::TARGET_PARAMETER);

        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->targets());
    }

    /** @test */
    public function shouldGetFirstElement()
    {
        $attributes = Attributes::from(Stub::class);
        $attributes = $attributes->first();

        $this->assertEquals(1, $attributes->count());
        $this->assertInstanceOf(ReflectionClass::class, $attributes->targets()[0]);
    }

    /** @test */
    public function shouldCheckIsEmpty()
    {
        $attributes = Attributes::from(Stub::class);
        $this->assertFalse($attributes->isEmpty());

        $attributes = new Attributes();
        $this->assertTrue($attributes->isEmpty());
    }

    /** @test */
    public function shouldGetInstances()
    {
        $attributes = Attributes::from(Stub::class, Attribute::TARGET_ALL, Attr1::class);
        $this->assertCount(5, $attributes->instances());
        $this->assertContainsOnlyInstancesOf(Attr1::class, $attributes->instances());
    }

    /** @test */
    public function shouldGetFirstInstance()
    {
        $attributes = Attributes::from(Stub::class, Attribute::TARGET_ALL, Attr1::class);
        $this->assertInstanceOf(Attr1::class, $attributes->firstInstance());
    }

    /** @test */
    public function shouldGetFirstInstanceWithDefault()
    {
        $attributes = new Attributes();
        $default = new stdClass();
        $this->assertSame($default, $attributes->firstInstance($default));
    }

    /** @test */
    public function shouldGetReflectionAttributes()
    {
        $attributes = Attributes::from(Stub::class);

        $this->assertCount(10, $attributes->attributes());
        $this->assertContainsOnlyInstancesOf(ReflectionAttribute::class, $attributes->attributes());
    }

    /** @test */
    public function shouldGetTargets()
    {
        $attributes = Attributes::from(Stub::class);
        $this->assertCount(10, $attributes->targets());
    }

    /** @test */
    public function shouldCheckAttributes()
    {
        $attributes = Attributes::from(Stub::class);
        $this->assertTrue($attributes->hasAttribute(Attr1::class));
        $this->assertTrue($attributes->hasAttribute(Attr2::class));
        $this->assertFalse($attributes->hasAttribute('other'));
    }

    /** @test */
    public function shouldCheckTarget()
    {
        $attributes = Attributes::from(Stub::class, Attribute::TARGET_METHOD);
        $this->assertTrue($attributes->hasTarget(Attribute::TARGET_METHOD));
        $this->assertFalse($attributes->hasTarget(Attribute::TARGET_CLASS));
    }

    /** @test */
    public function shouldCheckHasMany()
    {
        $attributes = Attributes::from(Stub::class);
        $this->assertTrue($attributes->hasMany(Attr1::class));

        $attributes = $attributes->first();
        $this->assertTrue($attributes->hasAttribute(Attr1::class));
        $this->assertFalse($attributes->hasMany(Attr1::class));
    }

    /** @test */
    public function shouldCheckHasTarget()
    {
        $attributes = Attributes::from(Stub::class, Attribute::TARGET_METHOD);
        $this->assertTrue($attributes->hasTarget(Attribute::TARGET_METHOD));
        $this->assertFalse($attributes->hasTarget(Attribute::TARGET_PARAMETER));
    }

    /** @test */
    public function shouldCountCollection()
    {
        $attributes = Attributes::from(Stub::class);
        $this->assertCount(10, $attributes);
        $attributes = $attributes->filter(fn()=> false);
        $this->assertCount(0, $attributes);
    }

    /** @test */
    public function shouldGetArray()
    {
        $attributes = Attributes::from(Stub::class);
        $array = $attributes->toArray();
        $this->assertCount(10, $array);
        $this->assertContainsOnlyInstancesOf(ParsedAttribute::class, $array);
    }

    /** @test */
    public function shouldIterateCollection()
    {
        $attributes = Attributes::from(Stub::class);
        foreach ($attributes as $attribute) {
            $this->assertInstanceOf(ParsedAttribute::class, $attribute);
        }
    }
}
