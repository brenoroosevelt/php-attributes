<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests;

use BrenoRoosevelt\PhpAttributes\Attr;
use BrenoRoosevelt\PhpAttributes\Exception\ClassConstantDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\ClassDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\FunctionDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\MethodDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Exception\PropertyDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Modifiers\Modifier;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Attr1;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Stub;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

class AttributesTest extends TestCase
{
    /** @test */
    public function shouldExtractFromClass(): void
    {
        $attributes = Attr::fromClass(Stub::class)->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetClass1', $instances[0]->id);
        $this->assertEquals('targetClass2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionClass::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldGetClassDoesNotExistExceptionWhenExtractFromClass(): void
    {
        $this->expectException(ClassDoesNotExists::class);
        Attr::fromClass('InvalidClass')->extract();
    }

    /** @test */
    public function shouldExtractFromConstants(): void
    {
        $attributes = Attr::fromClassConstants(Stub::class)->extract();
        $this->assertEquals(4, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstantC1', $instances[0]->id);
        $this->assertEquals('targetConstantC2', $instances[1]->id);
        $this->assertEquals('targetConstantD1', $instances[2]->id);
        $this->assertEquals('targetConstantD2', $instances[3]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionClassConstant::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromConstantsFiltering(): void
    {
        $attributes = Attr::fromClassConstants(Stub::class, [Modifier::IsPrivate])->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstantC1', $instances[0]->id);
        $this->assertEquals('targetConstantC2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionClassConstant::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromConstantsByName(): void
    {
        $attributes = Attr::fromClassConstants(Stub::class, [], 'D')->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstantD1', $instances[0]->id);
        $this->assertEquals('targetConstantD2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionClassConstant::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromConstantsFilteringAndByName(): void
    {
        $attributes =
            Attr::fromClassConstants(
                Stub::class,
                [Modifier::IsPrivate],
                'D'
            )->extract();

        $this->assertEquals(0, $attributes->count());
    }

    /** @test */
    public function shouldExtractFromConstantsByNameException(): void
    {
        $this->expectException(ClassConstantDoesNotExists::class);
        Attr::fromClassConstants(Stub::class, [], 'INVALID')->extract();
    }

    /** @test */
    public function shouldGetClassConstantDoesNotExistsException(): void
    {
        $this->expectException(ClassConstantDoesNotExists::class);
        Attr::fromClassConstants(Stub::class, [], 'INVALID')->extract();
    }

    /** @test */
    public function shouldGetClassDoesNotExistsExceptionWhenExtractConstants(): void
    {
        $this->expectException(ClassDoesNotExists::class);
        Attr::fromClassConstants('InvalidClass')->extract();
    }

    /** @test */
    public function shouldExtractFromProperty(): void
    {
        $attributes = Attr::fromProperties(Stub::class)->extract();
        $this->assertEquals(4, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetPropertyX1', $instances[0]->id);
        $this->assertEquals('targetPropertyX2', $instances[1]->id);
        $this->assertEquals('targetPropertyY1', $instances[2]->id);
        $this->assertEquals('targetPropertyY2', $instances[3]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionProperty::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromPropertyFiltering(): void
    {
        $attributes = Attr::fromProperties(Stub::class, [Modifier::IsPrivate])->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetPropertyX1', $instances[0]->id);
        $this->assertEquals('targetPropertyX2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionProperty::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromPropertyByName(): void
    {
        $attributes = Attr::fromProperties(
            Stub::class,
            [Modifier::IsProtected],
            'y'
        )->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetPropertyY1', $instances[0]->id);
        $this->assertEquals('targetPropertyY2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionProperty::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromPropertyByNameAndFiltering(): void
    {
        $attributes = Attr::fromProperties(Stub::class, [], 'y')->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetPropertyY1', $instances[0]->id);
        $this->assertEquals('targetPropertyY2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionProperty::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldGetPropertyDoesNotExistsWhenExtractProperties(): void
    {
        $this->expectException(PropertyDoesNotExists::class);
        Attr::fromProperties(Stub::class, [], 'invalid')->extract();
    }

    /** @test */
    public function shouldGetClassDoesNotExistsExceptionWhenExtractProperties(): void
    {
        $this->expectException(ClassDoesNotExists::class);
        Attr::fromProperties('InvalidClass')->extract();
    }


    /** @test */
    public function shouldExtractFromMethods(): void
    {
        $attributes = Attr::fromMethods(Stub::class)->extract();
        $this->assertEquals(8, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstructor1', $instances[0]->id);
        $this->assertEquals('targetConstructor2', $instances[1]->id);
        $this->assertEquals('targetMethodFoo1', $instances[2]->id);
        $this->assertEquals('targetMethodFoo2', $instances[3]->id);
        $this->assertEquals('targetMethodBar1', $instances[4]->id);
        $this->assertEquals('targetMethodBar2', $instances[5]->id);
        $this->assertEquals('targetClosure1', $instances[6]->id);
        $this->assertEquals('targetClosure2', $instances[7]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionMethod::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldGetMethodDoesNotExistsExceptionWhenExtractMethods(): void
    {
        $this->expectException(MethodDoesNotExists::class);
        Attr::fromMethods(Stub::class, [], 'invalid')->extract();
    }

    /** @test */
    public function shouldGetClassDoesNotExistsExceptionWhenExtractMethods(): void
    {
        $this->expectException(ClassDoesNotExists::class);
        Attr::fromMethods('InvalidClass')->extract();
    }

    /** @test */
    public function shouldExtractFromMethodsByName(): void
    {
        $attributes = Attr::fromMethods(Stub::class, [], 'foo', 'bar')->extract();
        $this->assertEquals(4, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetMethodFoo1', $instances[0]->id);
        $this->assertEquals('targetMethodFoo2', $instances[1]->id);
        $this->assertEquals('targetMethodBar1', $instances[2]->id);
        $this->assertEquals('targetMethodBar2', $instances[3]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionMethod::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromMethodsFiltering(): void
    {
        $attributes = Attr::fromMethods(Stub::class, [Modifier::IsProtected, Modifier::IsPrivate])->extract();
        $this->assertEquals(4, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetMethodFoo1', $instances[0]->id);
        $this->assertEquals('targetMethodFoo2', $instances[1]->id);
        $this->assertEquals('targetMethodBar1', $instances[2]->id);
        $this->assertEquals('targetMethodBar2', $instances[3]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionMethod::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromMethodsByNameAndFiltering(): void
    {
        $attributes = Attr::fromMethods(
            Stub::class,
            [Modifier::IsProtected, Modifier::IsPrivate],
            'foo',
            'bar'
        )->extract();
        $this->assertEquals(4, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetMethodFoo1', $instances[0]->id);
        $this->assertEquals('targetMethodFoo2', $instances[1]->id);
        $this->assertEquals('targetMethodBar1', $instances[2]->id);
        $this->assertEquals('targetMethodBar2', $instances[3]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionMethod::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromConstructor(): void
    {
        $attributes = Attr::fromConstructor(Stub::class)->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstructor1', $instances[0]->id);
        $this->assertEquals('targetConstructor2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionMethod::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldGetClassDoesNotExistsExceptionWhenExtractConstructor(): void
    {
        $this->expectException(ClassDoesNotExists::class);
        Attr::fromConstructor('InvalidClass')->extract();
    }

    /** @test */
    public function shouldGetEmptyCollectionWhenConstructorDoesNotExist(): void
    {
        $attributes = Attr::fromConstructor(new class {
        })->extract();
        $this->assertEquals(0, $attributes->count());
    }

    /** @test */
    public function shouldExtractFromConstructorParams(): void
    {
        $attributes = Attr::fromConstructorParams(Stub::class)->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstructorParamX', $instances[0]->id);
        $this->assertEquals('targetConstructorParamY', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromConstructorParamsByName(): void
    {
        $attributes = Attr::fromConstructorParams(Stub::class, 'y')->extract();
        $this->assertEquals(1, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetConstructorParamY', $instances[0]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldGetClassDoesNotExistsExceptionWhenExtractConstructorParams(): void
    {
        $this->expectException(ClassDoesNotExists::class);
        Attr::fromConstructorParams('InvalidClass')->extract();
    }

    /** @test */
    public function shouldExtractFromMethodsParams(): void
    {
        $attributes = Attr::fromMethodParams(Stub::class, 'foo')->extract();
        $this->assertEquals(2, $attributes->count());
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldGetClassDoesNotExistsExceptionWhenExtractMethodParams(): void
    {
        $this->expectException(ClassDoesNotExists::class);
        Attr::fromMethodParams('InvalidClass', 'foo')->extract();
    }

    /** @test */
    public function shouldGetMethodDoesNotExistsExceptionWhenExtractMethodParams(): void
    {
        $this->expectException(MethodDoesNotExists::class);
        Attr::fromMethodParams(Stub::class, 'invalid')->extract();
    }

    /** @test */
    public function shouldExtractFromMethodsParamsByName(): void
    {
        $attributes = Attr::fromMethodParams(Stub::class, 'foo', 'k')->extract();
        $this->assertEquals(2, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetParameterFoo1', $instances[0]->id);
        $this->assertEquals('targetParameterFoo2', $instances[1]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromFunction(): void
    {
        $fn = #[Attr1('targetFn')] function () {
        };

        $attributes = Attr::fromFunctions($fn)->extract();

        $this->assertEquals(1, $attributes->count());
        /** @var Attr1[] $instances */
        $instances = $attributes->getInstances();
        $this->assertEquals('targetFn', $instances[0]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionFunction::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromFunctionParams(): void
    {
        $fn = function (#[Attr1('targetFnParam')] $p) {
        };

        $attributes = Attr::fromFunctionParams($fn)->extract();

        $this->assertEquals(1, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetFnParam', $instances[0]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldExtractFromFunctionParamsByName(): void
    {
        $fn = function (#[Attr1('targetFnParam')] $p, #[Attr1('targetFnParam2')] $p2) {
        };

        $attributes = Attr::fromFunctionParams($fn, 'p2')->extract();

        $this->assertEquals(1, $attributes->count());
        $instances = $attributes->getInstances();
        $this->assertEquals('targetFnParam2', $instances[0]->id);
        $this->assertContainsOnlyInstancesOf(ReflectionParameter::class, $attributes->getTargets());
    }

    /** @test */
    public function shouldGetFunctionDoesNotExistsExceptionWhenExtractFunction(): void
    {
        $this->expectException(FunctionDoesNotExists::class);
        Attr::fromFunctions('invalid_fn')->extract();
    }

    /** @test */
    public function shouldGetFunctionDoesNotExistsExceptionWhenExtractFunctionParams(): void
    {
        Attr::fromProperties($this)->extract();

        $this->expectException(FunctionDoesNotExists::class);
        Attr::fromFunctionParams('invalid')->extract();
    }
}
