<?php
declare(strict_types=1);

namespace BrenoRosevelt\PhpAttributes\Tests;

use Attribute;
use BrenoRosevelt\PhpAttributes\AttributesFactory;
use BrenoRosevelt\PhpAttributes\Tests\Fixture\Attr1;
use BrenoRosevelt\PhpAttributes\Tests\Fixture\Attr2;
use BrenoRosevelt\PhpAttributes\Tests\Fixture\Stub;
use FlexFqcnFinder\Filter\Filter;
use FlexFqcnFinder\Fqcn;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

class AttributesFactoryTest extends TestCase
{
    /** @test */
    public function shouldExtractFromClass()
    {
        $attributes = AttributesFactory::fromClass(Stub::class, Attribute::TARGET_CLASS);
        $this->assertEquals(2, $attributes->count());
        $this->assertTrue($attributes->has(Attr1::class));
        $this->assertTrue($attributes->has(Attr2::class));
        $instances = $attributes->instances();
        $this->assertEquals('targetClass1', $instances[0]->id);
        $this->assertEquals('targetClass2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionClass::class, $attributes->targets());
    }

    /** @test */
    public function shouldExtractFromConstants()
    {
        $attributes = AttributesFactory::fromClass(Stub::class, Attribute::TARGET_CLASS_CONSTANT);
        $this->assertEquals(2, $attributes->count());
        $this->assertTrue($attributes->has(Attr1::class));
        $this->assertTrue($attributes->has(Attr2::class));
        $instances = $attributes->instances();
        $this->assertEquals('targetConstant1', $instances[0]->id);
        $this->assertEquals('targetConstant2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionClassConstant::class, $attributes->targets());
    }

    /** @test */
    public function shouldExtractFromProperty()
    {
        $attributes = AttributesFactory::fromClass(Stub::class, Attribute::TARGET_PROPERTY);
        $this->assertEquals(2, $attributes->count());
        $this->assertTrue($attributes->has(Attr1::class));
        $this->assertTrue($attributes->has(Attr2::class));
        $instances = $attributes->instances();
        $this->assertEquals('targetProperty1', $instances[0]->id);
        $this->assertEquals('targetProperty2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionProperty::class, $attributes->targets());
    }

    /** @test */
    public function shouldExtractFromMethods()
    {
        $attributes = AttributesFactory::fromClass(Stub::class, Attribute::TARGET_METHOD);
        $this->assertEquals(2, $attributes->count());
        $this->assertTrue($attributes->has(Attr1::class));
        $this->assertTrue($attributes->has(Attr2::class));
        $instances = $attributes->instances();
        $this->assertEquals('targetMethod1', $instances[0]->id);
        $this->assertEquals('targetMethod2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionMethod::class, $attributes->targets());
    }

    /** @test */
    public function shouldExtractFromParameters()
    {
        $attributes = AttributesFactory::fromClass(Stub::class, Attribute::TARGET_PARAMETER);
        $this->assertEquals(2, $attributes->count());
        $this->assertTrue($attributes->has(Attr1::class));
        $this->assertTrue($attributes->has(Attr2::class));
        $instances = $attributes->instances();
        $this->assertEquals('targetParameter1', $instances[0]->id);
        $this->assertEquals('targetParameter2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->targets());
    }

    /** @test */
    public function shouldExtractAllAttributes()
    {
        $attributes = AttributesFactory::fromClass(Stub::class);
        $this->assertEquals(10, $attributes->count());
        $this->assertTrue($attributes->has(Attr1::class));
        $this->assertTrue($attributes->has(Attr2::class));
        $expected = [
            'targetClass1',
            'targetClass2',
            'targetConstant1',
            'targetConstant2',
            'targetProperty1',
            'targetProperty2',
            'targetMethod1',
            'targetMethod2',
            'targetParameter1',
            'targetParameter2',
        ];

        foreach ($attributes->instances() as $instance) {
            $this->assertTrue(in_array($instance->id, $expected));
        }
    }

    /** @test */
    public function shouldExtractFromFqcnFinder()
    {
        $attributes = AttributesFactory::fromFqcnFinder(
            Fqcn::fromDir(__DIR__, true)
                ->withFilter(Filter::by()->hasMethod('foo'))
        );

        $this->assertEquals(10, $attributes->count());
        $this->assertTrue($attributes->has(Attr1::class));
        $this->assertTrue($attributes->has(Attr2::class));
        $expected = [
            'targetClass1',
            'targetClass2',
            'targetConstant1',
            'targetConstant2',
            'targetProperty1',
            'targetProperty2',
            'targetMethod1',
            'targetMethod2',
            'targetParameter1',
            'targetParameter2',
        ];

        foreach ($attributes->instances() as $instance) {
            $this->assertTrue(in_array($instance->id, $expected));
        }
    }
}
