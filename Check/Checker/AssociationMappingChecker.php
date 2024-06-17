<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Check\Checker;

use Doctrine\ORM\Mapping\AssociationMapping;
use Doctrine\ORM\Mapping\ManyToOneAssociationMapping;
use Doctrine\ORM\Mapping\ClassMetadata;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Doctrine\MetadataReaderInterface;
use Nighten\DoctrineCheck\Dto\Result;
use Nighten\DoctrineCheck\Check\Contract\AssociationMappingCheckerInterface;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Ignore\IgnoreStorage;
use Nighten\DoctrineCheck\Type\ErrorType;
use ReflectionClass;

class AssociationMappingChecker implements AssociationMappingCheckerInterface
{
    /**
     * @throws DoctrineCheckException
     */
    public function checkFieldAssociationMapping(
        string $fieldName,
        ClassMetadata $metadata,
        AssociationMapping $associationMapping,
        ReflectionClass $reflectionClass,
        DoctrineCheckConfig $config,
        MetadataReaderInterface $metadataReader,
        IgnoreStorage $ignoreStorage,
        Result $result,
    ): void {
        $className = $metadata->getName();
        if (!$associationMapping instanceof ManyToOneAssociationMapping) {
            //TODO: Add handle other association mapping types
            $result->addSkipped(
                $className,
                $fieldName,
                'Handle association mapping "' . $associationMapping::class . '" is not implemended yet',
            );
            return;
        }
        $result->addProcessedField($reflectionClass->getName(), $fieldName);
        $phpType = $config->getPhpTypeResolver()->resolve($fieldName, $metadata, $reflectionClass);

        $targetEntityClassName = $associationMapping->targetEntity;

        //CHECK TYPE

        $phpTypeNames = $phpType->getTypeNames();
        if (count($phpTypeNames) > 1) {
            //TODO: Add handle union php types
            $result->addSkipped(
                $className,
                $fieldName,
                'Handle PHP union types "' . implode('|', $phpTypeNames)
                . '" is not implemented yet',
            );
            return;
        }
        $phpTypeClassName = $phpTypeNames[array_key_first($phpTypeNames)];

        if ($phpTypeClassName !== $targetEntityClassName) {
            if (!$ignoreStorage->found($className, $fieldName, ErrorType::TYPE_WRONG_MAPPING_TYPE)) {
                $result->addWrongMappingType(
                    $className . ':' . $fieldName,
                    'Doctrine type "' . $targetEntityClassName . '" not matched with php type: '
                    . '"' . $phpTypeClassName . '"',
                );
            }
        }

        //CHECK NULL

        $joinColumns = $associationMapping->joinColumns;
        if (count($joinColumns) !== 1) {
            $texts = [];
            foreach ($joinColumns as $joinColumn) {
                $texts[] = $joinColumn->fieldName . ' <> ' . $joinColumn->referencedColumnName;
            }
            //TODO: Add handle not one join columns
            $result->addSkipped(
                $className,
                $fieldName,
                'Handle not not join columns "' . implode('|', $texts)
                . '" is not implemented yet',
            );
            return;
        } else {
            $joinColumn = $joinColumns[array_key_first($joinColumns)];
            $inDoctrineIsNullable = $joinColumn->nullable;
            if ($inDoctrineIsNullable !== $phpType->isAllowNull()) {
                $inDoctrineText = 'undefined';
                if (null !== $inDoctrineIsNullable) {
                    $inDoctrineText = $inDoctrineIsNullable ? 'nullable' : 'not nullable';
                }

                if (!$ignoreStorage->found($className, $fieldName, ErrorType::TYPE_WRONG_NULLABLE)) {
                    $result->addWrongNullable(
                        $className . ':' . $fieldName,
                        'Doctrine type ' . $inDoctrineText . ' but in php '
                        . ($phpType->isAllowNull() ? 'allow null' : 'not allow null'),
                    );
                }
            }
        }
    }
}
