<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Doctrine;

class DoctrineEmbeddedMapping
{
    private string | false | null $columnPrefix = null;
    private string | null $declaredField = null;
    private string | null $originalField = null;

    /**
     * @var class-string|null
     */
    private string | null $inherited = null;

    /**
     * @var class-string|null
     */
    private string | null $declared = null;

    public function __construct(
        private string $class,
    ) {
    }

    public function getColumnPrefix(): false | string | null
    {
        return $this->columnPrefix;
    }

    public function setColumnPrefix(false | string | null $columnPrefix): void
    {
        $this->columnPrefix = $columnPrefix;
    }

    public function getDeclaredField(): ?string
    {
        return $this->declaredField;
    }

    public function setDeclaredField(?string $declaredField): void
    {
        $this->declaredField = $declaredField;
    }

    public function getOriginalField(): ?string
    {
        return $this->originalField;
    }

    public function setOriginalField(?string $originalField): void
    {
        $this->originalField = $originalField;
    }

    public function getInherited(): ?string
    {
        return $this->inherited;
    }

    /**
     * @param class-string|null $inherited
     */
    public function setInherited(?string $inherited): void
    {
        $this->inherited = $inherited;
    }

    public function getDeclared(): ?string
    {
        return $this->declared;
    }

    /**
     * @param class-string|null $declared
     */
    public function setDeclared(?string $declared): void
    {
        $this->declared = $declared;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): void
    {
        $this->class = $class;
    }
}
