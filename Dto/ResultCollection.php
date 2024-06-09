<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Dto;

use Nighten\DoctrineCheck\Ignore\IgnoreStorage;

class ResultCollection
{
    /** @var Result[] */
    private array $results = [];

    private IgnoreStorage $ignores;

    public function __construct()
    {
        $this->ignores = new IgnoreStorage();
    }

    public function addResult(Result $result): void
    {
        $this->results[] = $result;
    }

    /**
     * @return Result[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    public function hasErrors(): bool
    {
        foreach ($this->results as $result) {
            if ($result->hasErrors()) {
                return true;
            }
        }
        return false;
    }

    public function getErrorsCount(): int
    {
        $count = 0;
        foreach ($this->results as $result) {
            $count += $result->getErrorsCount();
        }
        return $count;
    }

    public function getProcessedClassesCount(): int
    {
        $count = 0;
        foreach ($this->results as $result) {
            $count += $result->getProcessedClasses();
        }
        return $count;
    }

    public function getProcessedFieldsCount(): int
    {
        $count = 0;
        foreach ($this->results as $result) {
            $count += $result->getProcessedFields();
        }
        return $count;
    }

    public function getIgnoreStorage(): IgnoreStorage
    {
        return $this->ignores;
    }

    public function setIgnoreStorage(IgnoreStorage $ignores): void
    {
        $this->ignores = $ignores;
    }
}
