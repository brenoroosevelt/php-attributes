<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Tests\Fixture;

use Attribute;

#[Attribute]
class Attr1
{
    public function __construct(
        public string $id
    ){
    }
}
