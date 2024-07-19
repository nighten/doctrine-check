<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php\Resolver;

enum ResolveSource
{
    case PHPNative;
    case PHPDoc;
}
