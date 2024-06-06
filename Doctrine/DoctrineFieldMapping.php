<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Doctrine;

class DoctrineFieldMapping
{
    public ?bool $id = null;
    public ?bool $nullable = null;
    public ?bool $notInsertable = null;
    public ?bool $notUpdatable = null;
    public ?string $columnDefinition = null;
    /** @psalm-var ClassMetadata::GENERATED_*|null */
    public ?int $generated = null;
    /** @var class-string|null */
    public ?string $enumType = null;
    public ?int $precision = null;
    public ?int $scale = null;
    public ?int $length = null;
    public ?bool $unique = null;
    /** @var class-string|null */
    public ?string $inherited = null;
    public ?string $originalClass = null;
    public ?string $originalField = null;
    public ?bool $quoted = null;
    /** @var class-string|null */
    public ?string $declared = null;
    public ?string $declaredField = null;
    /** @var array<mixed>|null */
    public ?array $options = null;
    public ?bool $version = null;
    public string | int | null $default = null;

    public function __construct(
        private string $fieldName,
        private string $type,
        private string $columnName,
    ) {
    }

    public function setId(?bool $id): void
    {
        $this->id = $id;
    }

    public function setNullable(?bool $nullable): void
    {
        $this->nullable = $nullable;
    }

    public function setNotInsertable(?bool $notInsertable): void
    {
        $this->notInsertable = $notInsertable;
    }

    public function setNotUpdatable(?bool $notUpdatable): void
    {
        $this->notUpdatable = $notUpdatable;
    }

    public function setColumnDefinition(?string $columnDefinition): void
    {
        $this->columnDefinition = $columnDefinition;
    }

    public function setGenerated(?int $generated): void
    {
        $this->generated = $generated;
    }

    public function setEnumType(?string $enumType): void
    {
        $this->enumType = $enumType;
    }

    public function setPrecision(?int $precision): void
    {
        $this->precision = $precision;
    }

    public function setScale(?int $scale): void
    {
        $this->scale = $scale;
    }

    public function setLength(?int $length): void
    {
        $this->length = $length;
    }

    public function setUnique(?bool $unique): void
    {
        $this->unique = $unique;
    }

    public function setInherited(?string $inherited): void
    {
        $this->inherited = $inherited;
    }

    public function setOriginalClass(?string $originalClass): void
    {
        $this->originalClass = $originalClass;
    }

    public function setOriginalField(?string $originalField): void
    {
        $this->originalField = $originalField;
    }

    public function setQuoted(?bool $quoted): void
    {
        $this->quoted = $quoted;
    }

    public function setDeclared(?string $declared): void
    {
        $this->declared = $declared;
    }

    public function setDeclaredField(?string $declaredField): void
    {
        $this->declaredField = $declaredField;
    }

    public function setOptions(?array $options): void
    {
        $this->options = $options;
    }

    public function setVersion(?bool $version): void
    {
        $this->version = $version;
    }

    public function setDefault(int | string | null $default): void
    {
        $this->default = $default;
    }

    public function getId(): ?bool
    {
        return $this->id;
    }

    public function getNullable(): ?bool
    {
        return $this->nullable;
    }

    public function getNotInsertable(): ?bool
    {
        return $this->notInsertable;
    }

    public function getNotUpdatable(): ?bool
    {
        return $this->notUpdatable;
    }

    public function getColumnDefinition(): ?string
    {
        return $this->columnDefinition;
    }

    public function getGenerated(): ?int
    {
        return $this->generated;
    }

    public function getEnumType(): ?string
    {
        return $this->enumType;
    }

    public function getPrecision(): ?int
    {
        return $this->precision;
    }

    public function getScale(): ?int
    {
        return $this->scale;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function getUnique(): ?bool
    {
        return $this->unique;
    }

    public function getInherited(): ?string
    {
        return $this->inherited;
    }

    public function getOriginalClass(): ?string
    {
        return $this->originalClass;
    }

    public function getOriginalField(): ?string
    {
        return $this->originalField;
    }

    public function getQuoted(): ?bool
    {
        return $this->quoted;
    }

    public function getDeclared(): ?string
    {
        return $this->declared;
    }

    public function getDeclaredField(): ?string
    {
        return $this->declaredField;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function getVersion(): ?bool
    {
        return $this->version;
    }

    public function getDefault(): int | string | null
    {
        return $this->default;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getColumnName(): string
    {
        return $this->columnName;
    }
}
