<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Dto;

class ResultCollection
{
    /** @var Result[] */
    private array $results = [];

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
}
