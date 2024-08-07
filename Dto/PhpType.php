<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Dto;

use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Php\Resolver\ResolveSource;

class PhpType
{
    private bool $resolved = false;

    /** @var string[] */
    private array $typeNames;

    private bool $allowNull;

    private ResolveSource $resolveSource;

    private string $comment = '';

    public function isResolved(): bool
    {
        return $this->resolved;
    }

    /**
     * @return string[]
     * @throws DoctrineCheckException
     */
    public function getTypeNames(): array
    {
        if (!$this->resolved) {
            throw new DoctrineCheckException('Type names are not resolved.');
        }
        return $this->typeNames;
    }

    /**
     * @throws DoctrineCheckException
     */
    public function isAllowNull(): bool
    {
        if (!$this->resolved) {
            throw new DoctrineCheckException('Is allow null is not resolved.');
        }
        return $this->allowNull;
    }

    /**
     * @param string[] $typeNames
     */
    public function resolve(
        array $typeNames,
        bool $allowNull,
        ResolveSource $resolveSource,
    ): void {
        $this->typeNames = $typeNames;
        $this->allowNull = $allowNull;
        $this->resolveSource = $resolveSource;
        $this->resolved = true;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function isResolveSourcePhpNative(): bool
    {
        return $this->resolveSource === ResolveSource::PHPNative;
    }

    public function isResolveSourcePhpDoc(): bool
    {
        return $this->resolveSource === ResolveSource::PHPDoc;
    }
}
