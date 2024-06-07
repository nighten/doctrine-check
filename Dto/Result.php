<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Dto;

class Result
{
    /** @var array{"message": string, "type": string}[] */
    private array $errors = [];

    public const TYPE_MISSED_CONFIG_MAPPING = 'MISSED_CONFIG_MAPPING';
    public const TYPE_WRONG_MAPPING_TYPE = 'WRONG_MAPPING_TYPE';
    public const TYPE_WRONG_NULLABLE = 'WRONG_NULLABLE';

    public function addMissedConfigMappingError(string $fieldKey, string $message): void
    {
        $this->addError($fieldKey, $message, self::TYPE_MISSED_CONFIG_MAPPING);
    }

    public function addWrongMappingType(string $fieldKey, string $message): void
    {
        $this->addError($fieldKey, $message, self::TYPE_WRONG_MAPPING_TYPE);
    }

    public function addWrongNullble(string $fieldKey, string $message): void
    {
        $this->addError($fieldKey, $message, self::TYPE_WRONG_NULLABLE);
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
}
