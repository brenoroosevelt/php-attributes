<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Modifiers;

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
}
