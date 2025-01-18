<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php\PHPDocParser;

use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;

interface PHPDocParserInterface
{
    public function parse(
        string $docBlock,
        DoctrineCheckConfig $config,
    ): PHPDoc;
}
