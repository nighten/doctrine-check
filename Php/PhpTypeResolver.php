<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php;

use Doctrine\ORM\Mapping\ClassMetadata;
use Nighten\DoctrineCheck\Dto\PhpType;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionUnionType;

class PhpTypeResolver implements PhpTypeResolverInterface
{
    public function resolve(
        string $fieldName,
        ClassMetadata $metadata,
        ReflectionClass $reflectionClass,
    ): PhpType {
        $result = new PhpType();
        if (str_contains($fieldName, '.')) {
            //TODO: implement that logic (embedded)
            return $result;
        }
        if (!$reflectionClass->hasProperty($fieldName) && count($metadata->parentClasses) > 0) {
            //TODO: implement that logic (inheritance)
            return $result;
        }
        if (!$reflectionClass->hasProperty($fieldName)) {
            //TODO: implement that logic (inheritance)
            return $result;
        }
        $prop = $reflectionClass->getProperty($fieldName);
        $type = $prop->getType();
        if (null === $type) {
            //TODO: implement that logic (property without type)
            return $result;
        }
        $phpTypeNames = [];
        if ($type instanceof ReflectionNamedType) {
            $phpTypeNames[] = $type->getName();
            $phpTypeIsAllowsNull = $type->allowsNull();
        } elseif ($type instanceof ReflectionUnionType) {
            foreach ($type->getTypes() as $innerType) {
                if (!$innerType instanceof ReflectionNamedType) {
                    throw new DoctrineCheckException(
                        'Need to improve check doctrine mapping type test.'
                        . ' Context: $innerType is "' . $innerType::class . '"'
                    );
                }
                $phpTypeNames[] = $innerType->getName();
            }
            $phpTypeIsAllowsNull = $type->allowsNull();
        } else {
            throw new DoctrineCheckException(
                'Need to improve check doctrine mapping type test.'
                . ' Context: $type is "' . $type::class . '"'
            );
        }
        $result->resolve($phpTypeNames, $phpTypeIsAllowsNull);
        return $result;
    }
}
