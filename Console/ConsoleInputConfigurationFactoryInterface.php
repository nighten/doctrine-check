<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Console;

use Symfony\Component\Console\Input\InputInterface;

interface ConsoleInputConfigurationFactoryInterface
{
    public function getConsoleConfiguration(InputInterface $input): ConsoleConfiguration;
}
