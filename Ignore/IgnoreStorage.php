<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Ignore;

class IgnoreStorage
{
    /** @var array<string, true> */
    private array $ignores = [];

    /** @var array<string, true> */
    private array $found = [];

    /**
     * @return array<string, true>
     */
    public function getIgnores(): array
    {
        return $this->ignores;
    }

    public function getCountIgnores(): int
    {
        return count($this->ignores);
    }

    /**
     * @param class-string $className
     */
    public function addIgnore(string $className, string $field, string $errorType): void
    {
        $key = $this->getKey($className, $field, $errorType);
        $this->ignores[$key] = true;
    }

    /**
     * @param class-string $className
     */
    public function found(string $className, string $field, string $errorType): bool
    {
        $key = $this->getKey($className, $field, $errorType);
        if (!array_key_exists($key, $this->ignores)) {
            return false;
        }
        unset($this->ignores[$key]);
        $this->found[$key] = true;
        return true;
    }

    /**
     * @return array<string, true>
     */
    public function getFound(): array
    {
        return $this->found;
    }

    /**
     * @param class-string $className
     */
    private function getKey(string $className, string $field, string $errorType): string
    {
        return $className . ':' . $field . ' > ' . $errorType;
    }
}
