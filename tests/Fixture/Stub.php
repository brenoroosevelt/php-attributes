<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests\Fixture;

use stdClass;

#[Attr1('targetClass1')]
#[Attr2('targetClass2')]
class Stub implements StubInterface
{
    #[Attr1('targetConstant1')]
    #[Attr2('targetConstant2')]
    const C = 0;

    #[Attr1('targetProperty1')]
    #[Attr2('targetProperty2')]
    private int|self $x;

    #[Attr1('targetConstructor1')]
    #[Attr2('targetConstructor2')]
    public function __construct(
        #[Attr1('targetConstructorParamX')]
        int $x = 0,
        #[Attr1('targetConstructorParamY')]
        int $y = 1
    ) {
    }

    #[Attr2('targetMethod1')]
    #[Attr1('targetMethod2')]
    protected function foo(
        #[Attr1('targetParameter1')]
        #[Attr2('targetParameter2')]
        string $k
    ): float|stdClass|null|StubInterface {
        return 0.0;
    }
}
