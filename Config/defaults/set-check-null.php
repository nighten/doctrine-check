<?php

namespace Nighten\DoctrineCheck\Config\defaults;

use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Php\PhpTypeResolver;

return function (DoctrineCheckConfig $config): void {
    $config->setCheckNullAtIdFields(false);
};
