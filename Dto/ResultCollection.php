<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Dto;

class ResultCollection
{
    /** @var Result[] */
    private array $results = [];

    private bool $hasErrors = false;

    public function addResult(Result $result): void
    {
        $this->results[] = $result;
        if ($result->hasErrors()) {
            $this->hasErrors = true;
        }
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
        return $this->hasErrors;
    }
}
