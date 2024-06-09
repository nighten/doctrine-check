<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;

interface MetadataReaderInterface
{
    /**
     * @param ClassMetadata<object> $metadata
     */
    public function getFieldMapping(ClassMetadata $metadata, string $fieldName): DoctrineFieldMapping;
}
