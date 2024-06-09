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
        $doNotFailOnUslessIgnore = $input->hasOption('do-not-fail-on-usless-ignore')
            ? (bool)$input->getOption('do-not-fail-on-usless-ignore')
            : false;

        $configurator = new ConsoleConfiguration();
        $configurator->setHideIgnores($hideIgnores);
        $configurator->setDoNotFailOnUslessIgnore($doNotFailOnUslessIgnore);
        return $configurator;
    }
}
