<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Check\Checker;

use Doctrine\ORM\Mapping\ClassMetadata;
use Nighten\DoctrineCheck\Check\Contract\EmbeddedMappingCheckerInterface;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Doctrine\DoctrineEmbeddedMapping;
use Nighten\DoctrineCheck\Doctrine\MetadataReaderInterface;
use Nighten\DoctrineCheck\Dto\PhpType;
use Nighten\DoctrineCheck\Dto\Result;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Ignore\IgnoreStorage;
use Nighten\DoctrineCheck\Type\ErrorType;
use ReflectionClass;

class EmbeddedMappingChecker implements EmbeddedMappingCheckerInterface
{

    /**
     * @throws DoctrineCheckException
     */
    public function checkEmbedded(
        string $fieldName,
        ClassMetadata $metadata,
        ReflectionClass $reflectionClass,
        DoctrineCheckConfig $config,
        MetadataReaderInterface $metadataReader,
        IgnoreStorage $ignoreStorage,
        Result $result,
    ): void {
        $result->addProcessedField($reflectionClass->getName(), $fieldName);
        $phpType = $config->getPhpTypeResolver()->resolve(
            $reflectionClass,
            $fieldName,
            $metadata->parentClasses,
        );
        if (!$phpType->isResolved()) {
            //TODO: need implement resolve all php types and cases
            $result->addSkipped(
                $metadata->getName(),
                $fieldName,
                $phpType->getComment(),
            );
            return;
        }
        $doctrineEmbeddedMapping = $metadataReader->getEmbeddedMapping($metadata, $fieldName);

        $this->checkType($doctrineEmbeddedMapping, $phpType, $metadata, $ignoreStorage, $fieldName, $result);
        $this->checkNull($doctrineEmbeddedMapping, $phpType, $metadata, $ignoreStorage, $fieldName, $result);
    }

    /**
     * @param ClassMetadata<object> $metadata
     * @throws DoctrineCheckException
     */
    private function checkType(
        DoctrineEmbeddedMapping $doctrineEmbeddedMapping,
        PhpType $phpType,
        ClassMetadata $metadata,
        IgnoreStorage $ignoreStorage,
        string $fieldName,
        Result $result
    ): void {
        $embeddedClass = $doctrineEmbeddedMapping->getClass();
        $phpTypesFactInClass = $phpType->getTypeNames();
        if (!in_array($embeddedClass, $phpTypesFactInClass, true)) {
            $className = $metadata->getName();
            if (!$ignoreStorage->found($className, $fieldName, ErrorType::TYPE_WRONG_MAPPING_TYPE)) {
                $result->addWrongMappingType(
                    $className . ':' . $fieldName,
                    'Embeddable type "' . $embeddedClass . '" not accepted php type(s): '
                    . implode('|', $phpTypesFactInClass),
                );
            }
        }
    }

    /**
     * @param ClassMetadata<object> $metadata,
     * @throws DoctrineCheckException
     */
    private function checkNull(
        DoctrineEmbeddedMapping $doctrineEmbeddedMapping,
        PhpType $phpType,
        ClassMetadata $metadata,
        IgnoreStorage $ignoreStorage,
        string $fieldName,
        Result $result
    ): void {
        $isPhpNullable = $phpType->isAllowNull();
        if ($isPhpNullable) {
            $className = $metadata->getName();
            $embeddedClass = $doctrineEmbeddedMapping->getClass();
            if (!$ignoreStorage->found($className, $fieldName, ErrorType::TYPE_WRONG_NULLABLE)) {
                $result->addWrongNullable(
                    $className . ':' . $fieldName,
                    'Embeddable type ' . $embeddedClass . ' map on nullable php field',
                );
            }
        }
    }
}
