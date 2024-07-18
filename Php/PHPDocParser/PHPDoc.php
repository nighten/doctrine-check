<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php\PHPDocParser;

class PHPDoc
{
    /**
     * @var string[];
     */
    private array $types = [];

    private bool $allowNull = false;

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    public function addType(string $type): void
    {
        if ('null' === $type) {
            $this->allowNull = true;
        } else {
            $this->types[] = $type;
        }
    }

    public function isAllowNull(): bool
    {
        return $this->allowNull;
    }

    public function setAllowNull(bool $allowNull): void
    {
        $this->allowNull = $allowNull;
    }
}
