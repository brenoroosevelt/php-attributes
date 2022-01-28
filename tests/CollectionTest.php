<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests;

use BrenoRoosevelt\PhpAttributes\Filtering\ReflectionTypeHelper;
use BrenoRoosevelt\PhpAttributes\ParsedAttribtubeCollection;
use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Attr1;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Attr2;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Fixtures;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Stub;
use ReflectionAttribute;
use ReflectionProperty;

class CollectionTest extends TestCase
{
    /** @test */
    public function shouldMergeCollection(): void
    {
        $collection1 = new ParsedAttribtubeCollection(Fixtures::attr1());
        $collection2 = new ParsedAttribtubeCollection(Fixtures::attr2());
        $merged = $collection1->merge($collection2);
        $this->assertEquals(2, $merged->count());
    }

    /** @test */
    public function shouldAddNewValue(): void
    {
        $collection = new ParsedAttribtubeCollection(Fixtures::attr1());
        $this->assertEquals(1, $collection->count());
        $collection = $collection->add(Fixtures::attr2());
        $this->assertEquals(2, $collection->count());
    }

    /** @test */
    public function shouldGetFirstElement(): void
    {
        $collection = new ParsedAttribtubeCollection(Fixtures::attr2(), Fixtures::attr1());
        $first = $collection->first();
        $this->assertEquals('data2', $first->target->getName());
    }

    /** @test */
    public function shouldCheckIsEmpty(): void
    {
        $collection = new ParsedAttribtubeCollection(Fixtures::attr1());
        $this->assertFalse($collection->isEmpty());

        $collection = new ParsedAttribtubeCollection();
        $this->assertTrue($collection->isEmpty());
    }

    /** @test */
    public function shouldGetInstances(): void
    {
        $collection = new ParsedAttribtubeCollection(Fixtures::attr1(), Fixtures::attr2());
        $instances = $collection->getInstances();
        $this->assertCount(2, $instances);
        $this->assertInstanceOf(Attr1::class, $instances[0]);
        $this->assertInstanceOf(Attr2::class, $instances[1]);
    }

    /** @test */
    public function shouldGetReflectionAttributes(): void
    {
        $collection = new ParsedAttribtubeCollection(Fixtures::attr1(), Fixtures::attr2());
        $attributes = $collection->getAttributes();
        $this->assertCount(2, $attributes);
        $this->assertContainsOnlyInstancesOf(ReflectionAttribute::class, $attributes);
    }

    /** @test */
    public function shouldGetTargets(): void
    {
        $collection = new ParsedAttribtubeCollection(Fixtures::attr1(), Fixtures::attr2());
        $targets = $collection->getTargets();
        $this->assertCount(2, $targets);
        $this->assertContainsOnlyInstancesOf(ReflectionProperty::class, $targets);
    }

    /** @test */
    public function shouldCountCollection(): void
    {
        $collection1 = new ParsedAttribtubeCollection();
        $collection2 = new ParsedAttribtubeCollection(Fixtures::attr1(), Fixtures::attr2());
        $this->assertEquals(0, count($collection1));
        $this->assertEquals(2, count($collection2));
    }

    /** @test */
    public function shouldGetArray(): void
    {
        $collection = new ParsedAttribtubeCollection(Fixtures::attr1(), Fixtures::attr2());
        $array = $collection->toArray();
        $this->assertCount(2, $array);
        $this->assertContainsOnlyInstancesOf(ParsedAttribute::class, $array);
    }

    /** @test */
    public function shouldIterateCollection(): void
    {
        $attributes = new ParsedAttribtubeCollection(Fixtures::attr1(), Fixtures::attr2());
        foreach ($attributes as $attribute) {
            $this->assertInstanceOf(ParsedAttribute::class, $attribute);
        }
    }

    /** @test */
    public function shouldFilterAccept(): void
    {
        $collection = new ParsedAttribtubeCollection(Fixtures::attr1(), Fixtures::attr2());
        $filtered = $collection->accept(fn(ParsedAttribute $p) => $p->attribute->newInstance() instanceof Attr1);
        $this->assertCount(2, $collection);
        $this->assertCount(1, $filtered);
        $this->assertContainsOnlyInstancesOf(Attr1::class, $filtered->getInstances());
    }

    /** @test */
    public function shouldFilterReject(): void
    {
        $collection = new ParsedAttribtubeCollection(Fixtures::attr1(), Fixtures::attr2());
        $filtered = $collection->reject(fn(ParsedAttribute $p) => $p->attribute->newInstance() instanceof Attr1);
        $this->assertCount(2, $collection);
        $this->assertCount(1, $filtered);
        $this->assertContainsOnlyInstancesOf(Attr2::class, $filtered->getInstances());
    }
}
