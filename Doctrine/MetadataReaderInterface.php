<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Doctrine;

use Doctrine\Persistence\Mapping\ClassMetadata;

interface MetadataReaderInterface
{
    public function getFieldMapping(ClassMetadata $metadata, string $fieldName): DoctrineFieldMapping;
}
