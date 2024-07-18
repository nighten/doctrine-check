<?php

namespace Nighten\DoctrineCheck\Config\defaults;

use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;

return function (DoctrineCheckConfig $config): void {
    $config->setCheckNullAtIdFields(false);
};
