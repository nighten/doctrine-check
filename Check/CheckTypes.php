<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Check;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Doctrine\MetadataReaderInterface;
use Nighten\DoctrineCheck\Dto\Result;
use Nighten\DoctrineCheck\Dto\ResultCollection;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Ignore\IgnoreStorage;

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
        $hasEntityClasses = $config->hasEntityClasses();
        $hasExcludedEntityClasses = $config->hasExcludedEntityClasses();
        foreach ($allMetadata as $metadata) {
            if (!$metadata instanceof ClassMetadata) {
                throw new DoctrineCheckException(
                    'Expected instance of ' . ClassMetadata::class
                    . ' from doctrine, but ' . $metadata::class
                    . ' given. Need to upgrade lib for handle this situation',
                );
            }
            $check = true;
            if ($hasEntityClasses && !$config->existEntityClasses($metadata->getName())) {
                $check = false;
            }
            if ($hasExcludedEntityClasses && $config->existExcludedEntityClasses($metadata->getName())) {
                $check = false;
            }
            if ($check) {
                $this->checkEntity(
                    $metadata,
                    $config,
                    $metadataReader,
                    $ignoreStorage,
                    $result,
                );
            }
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
        $fieldMappingChecker = $config->getFieldMappingChecker();
        if (null !== $fieldMappingChecker) {
            foreach ($metadata->getFieldNames() as $fieldName) {
                $fieldMappingChecker->checkField(
                    $fieldName,
                    $metadata,
                    $reflectionClass,
                    $config,
                    $metadataReader,
                    $ignoreStorage,
                    $result,
                );
            }
        } else {
            $result->addWarning('Field mapping check skipped');
        }

        $associationMappingChecker = $config->getAssociationMappingChecker();
        if (null !== $associationMappingChecker) {
            foreach ($metadata->getAssociationMappings() as $fieldName => $associationMapping) {
                $associationMappingChecker->checkFieldAssociationMapping(
                    $fieldName,
                    $metadata,
                    $associationMapping,
                    $reflectionClass,
                    $config,
                    $metadataReader,
                    $ignoreStorage,
                    $result,
                );
            }
        } else {
            $result->addWarning('Association mapping check skipped');
        }
    }
}
