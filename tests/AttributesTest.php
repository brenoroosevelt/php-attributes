<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests;

use BrenoRoosevelt\PhpAttributes\Attr;
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
        $attributes = Attr::fromClass(Stub::class)->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetClass1', $instances[0]->id);
        $this->assertEquals('targetClass2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionClass::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromConstants()
    {
        $attributes = Attr::fromClassConstants(Stub::class)->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstant1', $instances[0]->id);
        $this->assertEquals('targetConstant2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionClassConstant::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromProperty()
    {
        $attributes = Attr::fromProperties(Stub::class)->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetProperty1', $instances[0]->id);
        $this->assertEquals('targetProperty2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionProperty::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromMethods()
    {
        $attributes = Attr::fromMethods(Stub::class, 'foo')->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetMethod1', $instances[0]->id);
        $this->assertEquals('targetMethod2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionMethod::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromConstructor()
    {
        $attributes = Attr::fromConstructor(Stub::class)->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstructor1', $instances[0]->id);
        $this->assertEquals('targetConstructor2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionMethod::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromConstructorParams()
    {
        $attributes = Attr::fromConstructorParams(Stub::class)->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstructorParamX', $instances[0]->id);
        $this->assertEquals('targetConstructorParamY', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromMethodsParams()
    {
        $attributes = Attr::fromMethodParams(Stub::class, 'foo')->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetParameter1', $instances[0]->id);
        $this->assertEquals('targetParameter2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->getTargets());
    }
}
