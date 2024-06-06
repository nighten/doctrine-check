<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Cli;

use Nighten\DoctrineCheck\Config\ConfigResolver;
use Nighten\DoctrineCheck\Check\CheckTypes;
use Nighten\DoctrineCheck\Dto\Result;
use Nighten\DoctrineCheck\Dto\ResultCollection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        'types',
        'Check doctrine types',
    )
]
class CheckTypesCommand extends Command
{
    public function __construct(
        private ConfigResolver $configResolver,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $results = (new CheckTypes())->check($this->configResolver->resolve());

        $this->printResults($results, $output);

        return $results->hasErrors() ? Command::FAILURE : Command::SUCCESS;
    }

    private function printResults(ResultCollection $results, OutputInterface $output): void
    {
        foreach ($results->getResults() as $result) {
            $this->printResult($result, $output);
        }
    }

    private function printResult(Result $result, OutputInterface $output): void
    {
        foreach ($result->getErrors() as $key => $error) {
            $output->writeln($key . ' > ' . $error['message']);
        }
    }
}
