<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Nighten\DoctrineCheck\Dto\PhpType;
use ReflectionClass;

interface PhpTypeResolverInterface
{
    public function resolve(string $fieldName, ClassMetadata $metadata, ReflectionClass $reflectionClass): PhpType;
}
