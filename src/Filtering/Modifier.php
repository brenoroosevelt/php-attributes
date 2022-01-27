<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Filtering;

// @codingStandardsIgnoreFile
enum Modifier: int
{
    case IsPublic = 1;
    case IsProtected = 2;
    case IsPrivate = 4;
    case IsReadOnly = 5;
    case IsStatic = 16;
    case IsFinal = 32;
    case IsAbstract = 64;

    public static function sum(Modifier ...$modifiers): ?int
    {
        return
            empty($modifiers) ?
                null :
                array_reduce($modifiers, fn($sum, Modifier $item) => $sum + $item->value, 0);
    }
}
