<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Console;

use Symfony\Component\Console\Input\InputInterface;

class ConsoleInputConfigurationFactory implements ConsoleInputConfigurationFactoryInterface
{
    public function getConsoleConfiguration(InputInterface $input): ConsoleConfiguration
    {
        $hideIgnores = $input->hasOption('hide-ignores')
            ? (bool)$input->getOption('hide-ignores')
            : false;
        $doNotFailOnUslessIgnore = $input->hasOption('do-not-fail-on-useless-ignore')
            ? (bool)$input->getOption('do-not-fail-on-useless-ignore')
            : false;
        $showSkipped = $input->hasOption('show-skipped')
            ? (bool)$input->getOption('show-skipped')
            : false;

        $configurator = new ConsoleConfiguration();
        $configurator->setHideIgnores($hideIgnores);
        $configurator->setDoNotFailOnUselessIgnore($doNotFailOnUslessIgnore);
        $configurator->setShowSkipped($showSkipped);
        return $configurator;
    }
}
