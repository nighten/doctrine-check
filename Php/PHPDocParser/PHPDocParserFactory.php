<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php\PHPDocParser;

class PHPDocParserFactory
{
    public static function create(): PHPDocParserInterface
    {
        return new PHPDocParser();
    }
}
