<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Console\Command;

use Nighten\DoctrineCheck\Check\CheckTypes;
use Nighten\DoctrineCheck\Config\ConfigResolver;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Console\ConsoleConfiguration;
use Nighten\DoctrineCheck\Dto\Result;
use Nighten\DoctrineCheck\Dto\ResultCollection;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

    protected function configure(): void
    {
        $this
            ->setDescription(
                'Execute a check doctrine mapping types.',
            )
            ->addOption(
                'hide-ignores',
                null,
                InputOption::VALUE_NONE,
                'Do not add to output ignores list.',
            )
            ->addOption(
                'show-skipped',
                null,
                InputOption::VALUE_NONE,
                'Show skipped checkes.',
            )
            ->addOption(
                'do-not-fail-on-usless-ignore',
                null,
                InputOption::VALUE_NONE,
                'Do not return error code when exsit ingnore which not found in errors.',
            );
    }

    /**
     * @throws DoctrineCheckException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = $this->configResolver->resolve();
        $consoleInputConfigurationFactory = $config->getConsoleInputConfigurationFactory();
        if (null === $consoleInputConfigurationFactory) {
            throw new DoctrineCheckException(
                'Set consoleInputConfigurationFactory by '
                . DoctrineCheckConfig::class . ' ::setConsoleInputConfigurationFactory',
            );
        }
        $consoleConfiguration = $consoleInputConfigurationFactory->getConsoleConfiguration($input);

        $results = (new CheckTypes())->check($this->configResolver->resolve());

        $this->printResults(
            $results,
            $config,
            $consoleConfiguration,
            $output,
        );

        $hasErrors = $results->hasErrors();

        if (!$consoleConfiguration->isDoNotFailOnUselessIgnore()) {
            $resultIgnoreStorage = $results->getIgnoreStorage();
            $count = $resultIgnoreStorage->getCountIgnores();
            if ($count > 0) {
                $output->writeln('Fail: Found ' . $count . ' usless ignore(s)');
                $hasErrors = true;
            }
        }

        return $hasErrors ? Command::FAILURE : Command::SUCCESS;
    }

    private function printResults(
        ResultCollection $results,
        DoctrineCheckConfig $config,
        ConsoleConfiguration $consoleConfiguration,
        OutputInterface $output,
    ): void {
        $hasErrors = $results->hasErrors();
        if ($hasErrors) {
            $output->writeln('Errors:');
            foreach ($results->getResults() as $result) {
                $this->printResult($result, $output);
            }
            $output->writeln('');
        }
        $withIgnoresText = '';
        $ignoreStorage = $config->getIgnoreStorage();
        if ($ignoreStorage->getCountIgnores() > 0) {
            if (!$consoleConfiguration->isHideIgnores()) {
                $output->writeln('Ignore(s):');
                foreach ($ignoreStorage->getIgnores() as $ignore => $true) {
                    $output->writeln(' ' . $ignore);
                }
                $output->writeln('');

                $resultIgnoreStorage = $results->getIgnoreStorage();
                if ($resultIgnoreStorage->getCountIgnores() > 0) {
                    $output->writeln('Found usless ignore(s):');
                    foreach ($resultIgnoreStorage->getIgnores() as $ignore => $true) {
                        $output->writeln(' ' . $ignore);
                    }
                    $output->writeln('');
                }
            }
            $withIgnoresText = ' with ' . $ignoreStorage->getCountIgnores() . ' ignore(s)';
        }
        $output->writeln(
            'Processed ' . $results->getProcessedFieldsCount() . ' field(s)'
            . ' in ' . $results->getProcessedClassesCount() . ' class(es)' . $withIgnoresText,
        );
        if ($results->hasErrors()) {
            $output->writeln('Found ' . $results->getErrorsCount() . ' errors');
        } else {
            $output->writeln('No error found');
        }
        if ($results->hasWarnings()) {
            $output->writeln('Found ' . $results->getWarningsCount() . ' warnings:');
            foreach ($results->getResults() as $result) {
                foreach ($result->getWarnings() as $warning) {
                    $output->writeln(' ' . $warning);
                }
            }
        }
        if ($consoleConfiguration->isShowSkipped() && $results->hasSkipped()) {
            $output->writeln($results->getSkippedCount() . ' skipped checks:');
            foreach ($results->getResults() as $result) {
                foreach ($result->getSkipped() as $skipped) {
                    $output->writeln(
                        ' ' . $skipped['class'] . ':' . $skipped['field'] . ' - ' . $skipped['reason'],
                    );
                }
            }
        }
    }

    private function printResult(Result $result, OutputInterface $output): void
    {
        foreach ($result->getErrors() as $key => $error) {
            $output->writeln(' ' . $key . ' > ' . $error['message']);
        }
    }
}
