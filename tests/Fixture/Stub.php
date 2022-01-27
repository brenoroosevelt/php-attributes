<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests\Fixture;

use stdClass;

#[Attr1('targetClass1')]
#[Attr2('targetClass2')]
final class Stub
{
    #[Attr1('targetConstantC1')]
    #[Attr2('targetConstantC2')]
    private const C = 0;

    #[Attr1('targetConstantD1')]
    #[Attr2('targetConstantD2')]
    public const D = 1;

    #[Attr1('targetPropertyX1')]
    #[Attr2('targetPropertyX2')]
    private int|self $x;

    #[Attr1('targetPropertyY1')]
    #[Attr2('targetPropertyY2')]
    protected int|self $y;

    #[Attr1('targetConstructor1')]
    #[Attr2('targetConstructor2')]
    public function __construct(
        #[Attr1('targetConstructorParamX')]
        int $x = 0,
        #[Attr1('targetConstructorParamY')]
        int $y = 1
    ) {
    }

    #[Attr2('targetMethodFoo1')]
    #[Attr1('targetMethodFoo2')]
    protected function foo(
        #[Attr1('targetParameterFoo1')]
        #[Attr2('targetParameterFoo2')]
        string $k
    ): float|stdClass|null {
        return 0.0;
    }

    #[Attr2('targetMethodBar1')]
    #[Attr1('targetMethodBar2')]
    private function bar(
        #[Attr1('targetParameterBar1')]
        #[Attr2('targetParameterBar2')]
        string $k
    ): float|stdClass|null {
        return 0.0;
    }

    #[Attr2('targetClosure1')]
    #[Attr1('targetClosure2')]
    public function _invoke(
        #[Attr1('targetParameterClosure1')]
        #[Attr2('targetParameterClosure2')]
        string $k
    ): float|stdClass|null {
        return 0.0;
    }
}
