<?php

namespace Nighten\DoctrineCheck\Config\defaults;

use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Console\ConsoleInputConfigurationFactory;

return function (DoctrineCheckConfig $config): void {
    $config->setConsoleInputConfigurationFactory(new ConsoleInputConfigurationFactory());
};
