<?php

namespace Nighten\DoctrineCheck\Config\defaults;

use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Php\PHPDocParser\PHPDocParserFactory;
use Nighten\DoctrineCheck\Php\Resolver\PhpTypeResolver;

return function (DoctrineCheckConfig $config): void {
    $config->setPhpTypeResolver(new PhpTypeResolver(PHPDocParserFactory::create()));
};
