<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Doctrine;

use Doctrine\ORM\Mapping\FieldMapping;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\Mapping\ClassMetadata;

class DefaultMetadataReader implements MetadataReaderInterface
{
    /**
     * @param ClassMetadata<object> $metadata
     * @throws MappingException
     */
    public function getFieldMapping(ClassMetadata $metadata, string $fieldName): DoctrineFieldMapping
    {
        /**
         * Depend on Doctrine Version
         * @var array{
         *      "fieldName": string,
         *      "type": string,
         *      "columnName": string,
         *      "scale"?: int|null,
         *      "length"?: int|null,
         *      "unique"?: bool|null,
         *      "nullable"?: bool|null,
         *      "precision"?: int|null,
         *  }|FieldMapping $fieldMapping
         */
        $fieldMapping = $metadata->getFieldMapping($fieldName);

        //Doctrine 2
        if (is_array($fieldMapping)) {
            return $this->createFromArray($fieldMapping);
        }

        //Doctrine 3
        $mapping = new DoctrineFieldMapping(
            $fieldMapping->fieldName,
            $fieldMapping->type,
            $fieldMapping->columnName,
        );
        $mapping->setId($fieldMapping->id);
        $mapping->setNullable($fieldMapping->nullable);
        $mapping->setNotInsertable($fieldMapping->notInsertable);
        $mapping->setNotUpdatable($fieldMapping->notUpdatable);
        $mapping->setColumnDefinition($fieldMapping->columnDefinition);
        $mapping->setGenerated($fieldMapping->generated);
        $mapping->setEnumType($fieldMapping->enumType);
        $mapping->setPrecision($fieldMapping->precision);
        $mapping->setScale($fieldMapping->scale);
        $mapping->setLength($fieldMapping->length);
        $mapping->setUnique($fieldMapping->unique);
        $mapping->setInherited($fieldMapping->inherited);
        $mapping->setOriginalClass($fieldMapping->originalClass);
        $mapping->setOriginalField($fieldMapping->originalField);
        $mapping->setQuoted($fieldMapping->quoted);
        $mapping->setDeclared($fieldMapping->declared);
        $mapping->setDeclaredField($fieldMapping->declaredField);
        $mapping->setOptions($fieldMapping->options);
        $mapping->setVersion($fieldMapping->version);
        $mapping->setDefault($fieldMapping->default);
        return $mapping;
    }

    /**
     * @param array{
     *     "fieldName": string,
     *     "type": string,
     *     "columnName": string,
     *     "scale"?: int|null,
     *     "length"?: int|null,
     *     "unique"?: bool|null,
     *     "nullable"?: bool|null,
     *     "precision"?: int|null,
     * } $fieldMapping
     */
    private function createFromArray(array $fieldMapping): DoctrineFieldMapping
    {
        $mapping = new DoctrineFieldMapping(
            $fieldMapping['fieldName'],
            $fieldMapping['type'],
            $fieldMapping['columnName'],
        );
        if (array_key_exists('scale', $fieldMapping)) {
            $mapping->setScale($fieldMapping['scale']);
        }
        if (array_key_exists('length', $fieldMapping)) {
            $mapping->setLength($fieldMapping['length']);
        }
        if (array_key_exists('unique', $fieldMapping)) {
            $mapping->setUnique($fieldMapping['unique']);
        }
        if (array_key_exists('nullable', $fieldMapping)) {
            $mapping->setNullable($fieldMapping['nullable']);
        }
        if (array_key_exists('precision', $fieldMapping)) {
            $mapping->setPrecision($fieldMapping['precision']);
        }
        return $mapping;
    }
}
