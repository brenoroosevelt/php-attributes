<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests\Fixture;

use BrenoRoosevelt\PhpAttributes\ParsedAttribute;

final class Fixtures
{
    #[Attr1('str1')]
    private string $data1;

    #[Attr2('str2')]
    private string $data2;

    public static function attr1(): ParsedAttribute
    {
        $reflectionProperty = new \ReflectionProperty(self::class, 'data1');
        return new ParsedAttribute($reflectionProperty->getAttributes()[0], $reflectionProperty);
    }

    public static function attr2(): ParsedAttribute
    {
        $reflectionProperty = new \ReflectionProperty(self::class, 'data2');
        return new ParsedAttribute($reflectionProperty->getAttributes()[0], $reflectionProperty);
    }
}