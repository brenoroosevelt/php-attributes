<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests;

use BrenoRoosevelt\PhpAttributes\Attributes;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Stub;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

class AttributesTest extends TestCase
{
    /** @test */
    public function shouldExtractFromClass()
    {
        $attributes = Attributes::extract()->fromClass(Stub::class);
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetClass1', $instances[0]->id);
        $this->assertEquals('targetClass2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionClass::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromConstants()
    {
        $attributes = Attributes::extract()->fromClassConstants(Stub::class);
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstant1', $instances[0]->id);
        $this->assertEquals('targetConstant2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionClassConstant::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromProperty()
    {
        $attributes = Attributes::extract()->fromProperties(Stub::class);
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetProperty1', $instances[0]->id);
        $this->assertEquals('targetProperty2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionProperty::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromMethods()
    {
        $attributes = Attributes::extract()->fromMethods(Stub::class, 'foo');
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetMethod1', $instances[0]->id);
        $this->assertEquals('targetMethod2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionMethod::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromConstructor()
    {
        $attributes = Attributes::extract()->fromConstructor(Stub::class);
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstructor1', $instances[0]->id);
        $this->assertEquals('targetConstructor2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionMethod::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromConstructorParameters()
    {
        $attributes = Attributes::extract()->fromConstructorParameters(Stub::class);
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstructorParamX', $instances[0]->id);
        $this->assertEquals('targetConstructorParamY', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromParameters()
    {
        $attributes = Attributes::extract()->fromParameters(Stub::class, 'foo');
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetParameter1', $instances[0]->id);
        $this->assertEquals('targetParameter2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->getTargets());
    }
}
