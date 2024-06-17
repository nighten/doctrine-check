<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Check\Checker;

use Doctrine\ORM\Mapping\AssociationMapping;
use Doctrine\ORM\Mapping\ClassMetadata;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Doctrine\DoctrineFieldMapping;
use Nighten\DoctrineCheck\Doctrine\MetadataReaderInterface;
use Nighten\DoctrineCheck\Dto\PhpType;
use Nighten\DoctrineCheck\Dto\Result;
use Nighten\DoctrineCheck\Check\Contract\FieldMappingCheckerInterface;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Ignore\IgnoreStorage;
use Nighten\DoctrineCheck\Type\ErrorType;
use ReflectionClass;

class FieldMappingChecker implements FieldMappingCheckerInterface
{
    /**
     * @throws DoctrineCheckException
     */
    public function checkField(
        string $fieldName,
        ClassMetadata $metadata,
        ReflectionClass $reflectionClass,
        DoctrineCheckConfig $config,
        MetadataReaderInterface $metadataReader,
        IgnoreStorage $ignoreStorage,
        Result $result,
    ): void {
        $result->addProcessedField($reflectionClass->getName(), $fieldName);
        $phpType = $config->getPhpTypeResolver()->resolve($fieldName, $metadata, $reflectionClass);
        if (!$phpType->isResolved()) {
            //TODO: need implement resolve all php types and cases
            $result->addSkipped(
                $metadata->getName(),
                $fieldName,
                $phpType->getComment(),
            );
            return;
        }
        $doctrineFiledMapping = $metadataReader->getFieldMapping($metadata, $fieldName);
        if (!$this->checkTypeInConfig(
            $config,
            $doctrineFiledMapping->getType(),
            $metadata->getName(),
            $fieldName,
            $ignoreStorage,
            $result,
        )) {
            return;
        }
        $this->checkType(
            $config,
            $phpType,
            $doctrineFiledMapping,
            $metadata->getName(),
            $fieldName,
            $ignoreStorage,
            $result,
        );
        $this->checkNull(
            $config,
            $phpType,
            $doctrineFiledMapping,
            $metadata->getName(),
            $fieldName,
            $ignoreStorage,
            $result,
        );
    }

    /**
     * @param class-string $className
     */
    private function checkTypeInConfig(
        DoctrineCheckConfig $config,
        string $type,
        string $className,
        string $fieldName,
        IgnoreStorage $ignoreStorage,
        Result $result,
    ): bool {
        if (!$config->hasDoctrineTypeMapping($type)) {
            if (!$ignoreStorage->found($className, $fieldName, ErrorType::TYPE_MISSED_CONFIG_MAPPING)) {
                $result->addMissedConfigMappingError(
                    $className . ':' . $fieldName,
                    'Type "' . $type . '" not found in map types '
                    . 'Add it in config by ' . DoctrineCheckConfig::class . '::addTypeMapping',
                );
                return false;
            }
        }
        return true;
    }

    /**
     * @param class-string $className
     * @throws DoctrineCheckException
     */
    private function checkType(
        DoctrineCheckConfig $config,
        PhpType $phpType,
        DoctrineFieldMapping $doctrineFiledMapping,
        string $className,
        string $fieldName,
        IgnoreStorage $ignoreStorage,
        Result $result,
    ): void {
        $doctrineType = $doctrineFiledMapping->getType();
        $phpTypesFactInClass = $phpType->getTypeNames();
        $phpTypesAcceptedForDoctrineType = $config->getTypeMapping($doctrineType);
        if (count(array_intersect($phpTypesFactInClass, $phpTypesAcceptedForDoctrineType)) === 0) {
            if (in_array($doctrineFiledMapping->getEnumType(), $phpTypesFactInClass, true)) {
                $isEnum = true;
                foreach ($phpTypesFactInClass as $phpTypeName) {
                    if (!enum_exists($phpTypeName)) {
                        $isEnum = false;
                    }
                }
                if ($isEnum) {
                    return;
                }
            }
            if (!$ignoreStorage->found($className, $fieldName, ErrorType::TYPE_WRONG_MAPPING_TYPE)) {
                $result->addWrongMappingType(
                    $className . ':' . $fieldName,
                    'Doctrine type "' . $doctrineType . '" not accepted php type(s): '
                    . implode('|', $phpTypesFactInClass),
                );
            }
        }
    }

    /**
     * @param class-string $className
     * @throws DoctrineCheckException
     */
    private function checkNull(
        DoctrineCheckConfig $config,
        PhpType $phpType,
        DoctrineFieldMapping $doctrineFiledMapping,
        string $className,
        string $fieldName,
        IgnoreStorage $ignoreStorage,
        Result $result,
    ): void {
        if (!$config->isCheckNullAtIdFields() && $doctrineFiledMapping->getId() === true) {
            return;
        }
        $inDoctrineIsNullable = $doctrineFiledMapping->getNullable();
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
