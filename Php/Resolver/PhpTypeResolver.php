<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Php\Resolver;

use Nighten\DoctrineCheck\Dto\PhpType;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Php\PHPDocParser\PHPDocParserInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionType;
use ReflectionUnionType;

class PhpTypeResolver implements PhpTypeResolverInterface
{
    public function __construct(
        private readonly PHPDocParserInterface $PHPDocParser,
    ) {
    }

    /**
     *
     * @throws DoctrineCheckException
     * @throws ReflectionException
     */
    public function resolve(
        string | ReflectionClass $class,
        string $fieldName,
        //TODO: probably need remove from this method
        array $metadataParentClasses,
    ): PhpType {
        if (is_string($class)) {
            $reflectionClass = new ReflectionClass($class);
        } else {
            $reflectionClass = $class;
        }
        $result = new PhpType();
        if (!$reflectionClass->hasProperty($fieldName) && count($metadataParentClasses) > 0) {
            $prop = null;
            foreach ($metadataParentClasses as $parentClass) {
                $reflectionParentClass = new ReflectionClass($parentClass);
                if ($reflectionParentClass->hasProperty($fieldName)) {
                    $prop = $reflectionParentClass->getProperty($fieldName);
                }
            }
            if (null === $prop) {
                $result->setComment(
                    'Case with handle inheritance is not implemented yet. Parent classes: '
                    . implode('|', $metadataParentClasses)
                );
                return $result;
            }
        } elseif (!$reflectionClass->hasProperty($fieldName)) {
            //TODO: implement that logic (inheritance)
            $result->setComment('Handle inheritance is not implemented yet. Class does not have property.');
            return $result;
        } else {
            $prop = $reflectionClass->getProperty($fieldName);
        }
        $type = $prop->getType();
        if (null === $type) {
            $this->resolveFromPHPDoc($prop, $result);
            return $result;
        }

        $this->resolveFromPHP($type, $result);
        return $result;
    }

    private function resolveFromPHPDoc(ReflectionProperty $prop, PhpType $result): void
    {
        $docComment = $prop->getDocComment();
        if (false === $docComment) {
            //TODO: implement that logic (property without type)
            $result->setComment('Handle property without type is not implemented yet.');
            return;
        }
        $phpDoc = $this->PHPDocParser->parse($docComment);
        $phpDocTypes = $phpDoc->getTypes();
        if ([] === $phpDocTypes) {
            //TODO: implement that logic (property without type)
            $result->setComment('Handle property without type is not implemented yet.');
            return;
        }

        $result->resolve(
            $phpDoc->getTypes(),
            $phpDoc->isAllowNull(),
            ResolveSource::PHPDoc,
        );
    }

    /**
     * @throws DoctrineCheckException
     */
    private function resolveFromPHP(ReflectionType $type, PhpType $result): void
    {
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
        $result->resolve(
            $phpTypeNames,
            $phpTypeIsAllowsNull,
            ResolveSource::PHPNative,
        );
    }
}
