<?php
declare(strict_types=1);

namespace BrenoRosevelt\PhpAttributes\Tests\Fixture;

use Attribute;

#[Attribute]
class Attr1
{
    public function __construct(
        public string $id
    ){
    }
}
