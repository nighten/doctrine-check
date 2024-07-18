<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php\PHPDocParser;

interface PHPDocParserInterface
{
    public function parse(string $docBlock): PHPDoc;
}
