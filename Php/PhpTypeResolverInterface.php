<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php;

use Doctrine\ORM\Mapping\ClassMetadata;
use Nighten\DoctrineCheck\Dto\PhpType;
use ReflectionClass;

interface PhpTypeResolverInterface
{
    /**
     * @param ClassMetadata<object> $metadata
     * @param ReflectionClass<object> $reflectionClass
     */
    public function resolve(string $fieldName, ClassMetadata $metadata, ReflectionClass $reflectionClass): PhpType;
}
