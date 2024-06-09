<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Check;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Nighten\DoctrineCheck\Doctrine\DoctrineFieldMapping;
use Nighten\DoctrineCheck\Doctrine\MetadataReaderInterface;
use Nighten\DoctrineCheck\Php\PhpTypeResolver;
use ReflectionClass;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Dto\PhpType;
use Nighten\DoctrineCheck\Dto\Result;
use Nighten\DoctrineCheck\Dto\ResultCollection;

class CheckTypes
{
    /**
     * @throws DoctrineCheckException
     */
    public function check(DoctrineCheckConfig $config): ResultCollection
    {
        $result = new ResultCollection();
        foreach ($config->getObjectManagers() as $objectManager) {
            $result->addResult($this->checkObjectManager($config, $objectManager));
        }
        return $result;
    }

    /**
     * @throws DoctrineCheckException
     */
    public function checkObjectManager(DoctrineCheckConfig $config, ObjectManager $objectManager): Result
    {
        $allMetadata = $objectManager->getMetadataFactory()->getAllMetadata();
        $result = new Result();
        foreach ($allMetadata as $metadata) {
            $this->checkEntity($metadata, $config, $config->getMetadataReader($objectManager), $result);
        }
        return $result;
    }

    /**
     * @throws DoctrineCheckException
     */
    private function checkEntity(
        ClassMetadata $metadata,
        DoctrineCheckConfig $config,
        MetadataReaderInterface $metadataReader,
        Result $result,
    ): void {
        $reflectionClass = $metadata->getReflectionClass();
        if (null === $reflectionClass) {
            throw new DoctrineCheckException('Fail while getting ReflectionClass for ' . $metadata->getName());
        }
        foreach ($metadata->getFieldNames() as $fieldName) {
            $this->checkField($fieldName, $metadata, $reflectionClass, $config, $metadataReader, $result);
        }
    }

    /**
     * @throws DoctrineCheckException
     */
    private function checkField(
        string $fieldName,
        ClassMetadata $metadata,
        ReflectionClass $reflectionClass,
        DoctrineCheckConfig $config,
        MetadataReaderInterface $metadataReader,
        Result $result,
    ): void {
        $result->addProcessedField($reflectionClass->getName(), $fieldName);
        $filedKey = $metadata->getName() . ':' . $fieldName;
        $phpType = $config->getPhpTypeResolver()->resolve($fieldName, $metadata, $reflectionClass);
        if (!$phpType->isResolved()) {
            //TODO: need implement resolve all php types and cases
            return;
        }
        $doctrineFiledMapping = $metadataReader->getFieldMapping($metadata, $fieldName);
        if (!$this->checkTypeInConfig($config, $doctrineFiledMapping->getType(), $filedKey, $result)) {
            return;
        }
        $this->checkType($config, $phpType, $doctrineFiledMapping, $filedKey, $result);
        $this->checkNull($config, $phpType, $doctrineFiledMapping, $filedKey, $result);
    }

    private function checkTypeInConfig(
        DoctrineCheckConfig $config,
        string $type,
        string $filedKey,
        Result $result,
    ): bool {
        if (!$config->hasDoctrineTypeMapping($type)) {
            $result->addMissedConfigMappingError(
                $filedKey,
                'Type "' . $doctrineFiledMapping->getType() . '" not found in map types '
                . 'Add it in config by ' . DoctrineCheckConfig::class . '::addTypeMapping',
            );
            return false;
        }
        return true;
    }

    private function checkType(
        DoctrineCheckConfig $config,
        PhpType $phpType,
        DoctrineFieldMapping $doctrineFiledMapping,
        string $filedKey,
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
            $result->addWrongMappingType(
                $filedKey,
                'Doctrine type "' . $doctrineType . '" not accepted php type(s): '
                . implode('|', $phpTypesFactInClass),
            );
        }
    }

    private function checkNull(
        DoctrineCheckConfig $config,
        PhpType $phpType,
        DoctrineFieldMapping $doctrineFiledMapping,
        string $filedKey,
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
            $result->addWrongNullble(
                $filedKey,
                'Doctrine type ' . $inDoctrineText . ' but in php '
                . ($phpType->isAllowNull() ? 'allow null' : 'not allow null'),
            );
        }
    }
}
