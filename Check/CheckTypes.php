<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Check;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Doctrine\DoctrineFieldMapping;
use Nighten\DoctrineCheck\Doctrine\MetadataReaderInterface;
use Nighten\DoctrineCheck\Dto\PhpType;
use Nighten\DoctrineCheck\Dto\Result;
use Nighten\DoctrineCheck\Dto\ResultCollection;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Ignore\IgnoreStorage;
use Nighten\DoctrineCheck\Type\ErrorType;
use ReflectionClass;

class CheckTypes
{
    /**
     * @throws DoctrineCheckException
     */
    public function check(DoctrineCheckConfig $config): ResultCollection
    {
        $result = new ResultCollection();
        $ignoreStorage = clone $config->getIgnoreStorage();
        foreach ($config->getObjectManagers() as $objectManager) {
            $result->addResult($this->checkObjectManager($config, $objectManager, $ignoreStorage));
        }
        $result->setIgnoreStorage($ignoreStorage);
        return $result;
    }

    /**
     * @throws DoctrineCheckException
     */
    public function checkObjectManager(
        DoctrineCheckConfig $config,
        ObjectManager $objectManager,
        IgnoreStorage $ignoreStorage,
    ): Result {
        $allMetadata = $objectManager->getMetadataFactory()->getAllMetadata();
        $result = new Result();
        $metadataReader = $config->getMetadataReader($objectManager);
        if (null === $metadataReader) {
            throw new DoctrineCheckException(
                'Set metadata reader is required. Use ' . DoctrineCheckConfig::class
                . '::setMetadataReader for set',
            );
        }
        foreach ($allMetadata as $metadata) {
            if (!$metadata instanceof ClassMetadata) {
                throw new DoctrineCheckException(
                    'Expected instance of ' . ClassMetadata::class
                    . ' from doctrine, but ' . $metadata::class
                    . ' given. Need to upgrade lib for handle this situation',
                );
            }
            $this->checkEntity(
                $metadata,
                $config,
                $metadataReader,
                $ignoreStorage,
                $result,
            );
        }
        return $result;
    }

    /**
     * @param ClassMetadata<object> $metadata
     * @throws DoctrineCheckException
     */
    private function checkEntity(
        ClassMetadata $metadata,
        DoctrineCheckConfig $config,
        MetadataReaderInterface $metadataReader,
        IgnoreStorage $ignoreStorage,
        Result $result,
    ): void {
        $reflectionClass = $metadata->getReflectionClass();
        if (null === $reflectionClass) {
            throw new DoctrineCheckException('Fail while getting ReflectionClass for ' . $metadata->getName());
        }
        foreach ($metadata->getFieldNames() as $fieldName) {
            $this->checkField(
                $fieldName,
                $metadata,
                $reflectionClass,
                $config,
                $metadataReader,
                $ignoreStorage,
                $result,
            );
        }
    }

    /**
     * @param ClassMetadata<object> $metadata
     * @param ReflectionClass<object> $reflectionClass
     * @throws DoctrineCheckException
     */
    private function checkField(
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
                $result->addWrongNullble(
                    $className . ':' . $fieldName,
                    'Doctrine type ' . $inDoctrineText . ' but in php '
                    . ($phpType->isAllowNull() ? 'allow null' : 'not allow null'),
                );
            }
        }
    }
}
