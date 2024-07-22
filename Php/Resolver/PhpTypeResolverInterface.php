<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php\Resolver;

use Nighten\DoctrineCheck\Dto\PhpType;
use ReflectionClass;

interface PhpTypeResolverInterface
{
    /**
     * @param class-string|ReflectionClass<object> $class
     * @param class-string[] $metadataParentClasses
     */
    public function resolve(string | ReflectionClass $class, string $fieldName, array $metadataParentClasses): PhpType;
}
