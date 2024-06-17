<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Check\Contract;

use Doctrine\ORM\Mapping\AssociationMapping;
use Doctrine\ORM\Mapping\ClassMetadata;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Doctrine\MetadataReaderInterface;
use Nighten\DoctrineCheck\Dto\Result;
use Nighten\DoctrineCheck\Ignore\IgnoreStorage;
use ReflectionClass;

interface AssociationMappingCheckerInterface
{
    /**
     * @param ClassMetadata<object> $metadata
     * @param ReflectionClass<object> $reflectionClass
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
    ): void;
}
