<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests\Fixture;

#[Attr1('targetClass1')]
#[Attr2('targetClass2')]
class Stub
{
    #[Attr1('targetConstant1')]
    #[Attr2('targetConstant2')]
    const C = 0;

    #[Attr1('targetProperty1')]
    #[Attr2('targetProperty2')]
    private int $x;

    #[Attr2('targetMethod1')]
    #[Attr1('targetMethod2')]
    protected function foo(
        #[Attr1('targetParameter1')]
        #[Attr2('targetParameter2')]
        string $k
    ){
    }
}
