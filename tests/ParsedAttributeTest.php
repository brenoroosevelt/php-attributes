<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests;

use BrenoRoosevelt\PhpAttributes\Attributes;
use BrenoRoosevelt\PhpAttributes\ParsedAttribute;
use BrenoRoosevelt\PhpAttributes\Tests\Fixture\Stub;
use ReflectionClass;

class ParsedAttributeTest extends TestCase
{
    /** @test */
    public function shouldConstructAndGetCorrectValues(): void
    {
        $target = new ReflectionClass(Stub::class);
        $attribute = $target->getAttributes()[0];
        $parsedAttribute = new ParsedAttribute($attribute, $target);

        $this->assertSame($attribute, $parsedAttribute->attribute);
        $this->assertSame($target, $parsedAttribute->target);
    }
}
