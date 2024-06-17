<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Dto;

use Nighten\DoctrineCheck\Type\ErrorType;

class Result
{
    /** @var array<class-string, string[]> */
    private array $processedFields = [];

    /** @var array{"class": string, "field": string, "reason": string}[] */
    private array $skipped = [];

    /** @var string[] */
    private array $warnings = [];

    /** @var array{"message": string, "type": string}[] */
    private array $errors = [];

    /**
     * @param class-string $className
     */
    public function addProcessedField(string $className, string $fieldName): void
    {
        if (!array_key_exists($className, $this->processedFields)) {
            $this->processedFields[$className] = [];
        }
        $this->processedFields[$className][] = $fieldName;
    }

    public function addMissedConfigMappingError(string $fieldKey, string $message): void
    {
        $this->addError($fieldKey, $message, ErrorType::TYPE_MISSED_CONFIG_MAPPING);
    }

    public function addWrongMappingType(string $fieldKey, string $message): void
    {
        $this->addError($fieldKey, $message, ErrorType::TYPE_WRONG_MAPPING_TYPE);
    }

    public function addWrongNullable(string $fieldKey, string $message): void
    {
        $this->addError($fieldKey, $message, ErrorType::TYPE_WRONG_NULLABLE);
    }

    public function addError(string $fieldKey, string $message, string $type): void
    {
        $this->errors[$fieldKey] = [
            'message' => $message,
            'type' => $type,
        ];
    }

    /**
     * @return array{"message": string, "type": string}[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getErrorsCount(): int
    {
        return count($this->errors);
    }

    /**
     * @return string[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function addWarning(string $message): void
    {
        $this->warnings[$message] = $message;
    }

    public function hasWarnings(): bool
    {
        return count($this->warnings) > 0;
    }

    public function getWarningsCount(): int
    {
        return count($this->warnings);
    }

    /**
     * @return array{"class": string, "field": string, "reason": string}[]
     */
    public function getSkipped(): array
    {
        return $this->skipped;
    }

    public function addSkipped(string $className, string $field, string $reason): void
    {
        $this->skipped[] = [
            'class' => $className,
            'field' => $field,
            'reason' => $reason,
        ];
    }

    public function hasSkipped(): bool
    {
        return count($this->skipped) > 0;
    }

    public function getSkippedCount(): int
    {
        return count($this->skipped);
    }

    public function getProcessedClasses(): int
    {
        return count($this->processedFields);
    }

    public function getProcessedFields(): int
    {
        $count = 0;
        foreach ($this->processedFields as $fields) {
            $count += count($fields);
        }
        return $count;
    }
}
