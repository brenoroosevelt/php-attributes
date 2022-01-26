<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests;

use BrenoRoosevelt\PhpAttributes\Collection;
use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Attr1;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Attr2;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Fixtures;
use ReflectionAttribute;
use ReflectionProperty;

class CollectionTest extends TestCase
{
    /** @test */
    public function shouldMergeCollection()
    {
        $collection1 = new Collection(Fixtures::attr1());
        $collection2 = new Collection(Fixtures::attr2());
        $merged = $collection1->merge($collection2);
        $this->assertEquals(2, $merged->count());
    }

    /** @test */
    public function shouldAddNewValue()
    {
        $collection = new Collection(Fixtures::attr1());
        $this->assertEquals(1, $collection->count());
        $collection = $collection->add(Fixtures::attr2());
        $this->assertEquals(2, $collection->count());
    }

    /** @test */
    public function shouldGetFirstElement()
    {
        $collection = new Collection(Fixtures::attr2(), Fixtures::attr1());
        $first = $collection->first();
        $this->assertEquals('data2', $first->target()->getName());
    }

    /** @test */
    public function shouldCheckIsEmpty()
    {
        $collection = new Collection(Fixtures::attr1());
        $this->assertFalse($collection->isEmpty());

        $collection = new Collection();
        $this->assertTrue($collection->isEmpty());
    }

    /** @test */
    public function shouldGetInstances()
    {
        $collection = new Collection(Fixtures::attr1(), Fixtures::attr2());
        $instances = $collection->getInstances();
        $this->assertCount(2, $instances);
        $this->assertInstanceOf(Attr1::class, $instances[0]);
        $this->assertInstanceOf(Attr2::class, $instances[1]);
    }

    /** @test */
    public function shouldGetReflectionAttributes()
    {
        $collection = new Collection(Fixtures::attr1(), Fixtures::attr2());
        $attributes = $collection->getAttributes();
        $this->assertCount(2, $attributes);
        $this->assertContainsOnlyInstancesOf(ReflectionAttribute::class, $attributes);
    }

    /** @test */
    public function shouldGetTargets()
    {
        $collection = new Collection(Fixtures::attr1(), Fixtures::attr2());
        $targets = $collection->getTargets();
        $this->assertCount(2, $targets);
        $this->assertContainsOnlyInstancesOf(ReflectionProperty::class, $targets);
    }

    /** @test */
    public function shouldCountCollection()
    {
        $collection1 = new Collection();
        $collection2 = new Collection(Fixtures::attr1(), Fixtures::attr2());
        $this->assertEquals(0, count($collection1));
        $this->assertEquals(2, count($collection2));
    }

    /** @test */
    public function shouldGetArray()
    {
        $collection = new Collection(Fixtures::attr1(), Fixtures::attr2());
        $array = $collection->toArray();
        $this->assertCount(2, $array);
        $this->assertContainsOnlyInstancesOf(ParsedAttribute::class, $array);
    }

    /** @test */
    public function shouldIterateCollection()
    {
        $attributes = new Collection(Fixtures::attr1(), Fixtures::attr2());
        foreach ($attributes as $attribute) {
            $this->assertInstanceOf(ParsedAttribute::class, $attribute);
        }
    }
}
