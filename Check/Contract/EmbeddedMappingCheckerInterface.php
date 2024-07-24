<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Check\Contract;

use Doctrine\ORM\Mapping\ClassMetadata;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Doctrine\MetadataReaderInterface;
use Nighten\DoctrineCheck\Dto\Result;
use Nighten\DoctrineCheck\Ignore\IgnoreStorage;
use ReflectionClass;

interface EmbeddedMappingCheckerInterface
{
    /**
     * @param ClassMetadata<object> $metadata
     * @param ReflectionClass<object> $reflectionClass
     */
    public function checkEmbedded(
        string $fieldName,
        ClassMetadata $metadata,
        ReflectionClass $reflectionClass,
        DoctrineCheckConfig $config,
        MetadataReaderInterface $metadataReader,
        IgnoreStorage $ignoreStorage,
        Result $result,
    ): void;
}
