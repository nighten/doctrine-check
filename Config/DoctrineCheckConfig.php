<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Config;

use Doctrine\Persistence\ObjectManager;
use Nighten\DoctrineCheck\Doctrine\MetadataReaderInterface;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Php\PhpTypeResolverInterface;

class DoctrineCheckConfig
{
    /** @var array<string, string[]> */
    private array $typeMapping = [];

    /** @var array<int, ObjectManager> */
    private array $objectManagers = [];

    /** @var array<int, MetadataReaderInterface> */
    private array $objectManagerMetadataReaders = [];

    private ?MetadataReaderInterface $metadataReader = null;

    private ?PhpTypeResolverInterface $phpTypeResolver = null;

    private bool $checkNullAtIdFields = true;

    public function addObjectManager(
        ObjectManager $objectManager,
        ?MetadataReaderInterface $metadataReader = null,
    ): void {
        $this->objectManagers[spl_object_id($objectManager)] = $objectManager;
        if (null !== $metadataReader) {
            $this->objectManagerMetadataReaders[spl_object_id($objectManager)] = $metadataReader;
        }
    }

    /**
     * @param ObjectManager[] $objectManagers
     */
    public function setObjectManagers(array $objectManagers): void
    {
        $this->objectManagers = [];
        foreach ($objectManagers as $objectManager) {
            $this->addObjectManager($objectManager);
        }
    }

    /**
     * @return ObjectManager[]
     */
    public function getObjectManagers(): array
    {
        return $this->objectManagers;
    }

    public function addTypeMapping(string $doctrineType, string $phpType): void
    {
        if (!array_key_exists($doctrineType, $this->typeMapping)) {
            $this->typeMapping[$doctrineType] = [];
        }
        if (!in_array($phpType, $this->typeMapping[$doctrineType], true)) {
            $this->typeMapping[$doctrineType][] = $phpType;
        }
    }

    /**
     * @param string[] $phpTypes
     */
    public function setTypeMapping(string $doctrineType, array $phpTypes): void
    {
        $this->typeMapping[$doctrineType] = [];
        foreach ($phpTypes as $phpType) {
            $this->addTypeMapping($doctrineType, $phpType);
        }
    }

    public function hasDoctrineTypeMapping(string $doctrineType): bool
    {
        return array_key_exists($doctrineType, $this->typeMapping);
    }

    /**
     * @return array<string, string[]>
     */
    public function getTypesMapping(): array
    {
        return $this->typeMapping;
    }

    /**
     * @return string[]
     * @throws DoctrineCheckException
     */
    public function getTypeMapping(string $doctrineType): array
    {
        if (!$this->hasDoctrineTypeMapping($doctrineType)) {
            throw new DoctrineCheckException(
                'Doctrine type "' . $doctrineType . '"'
                . ' not found at config. Add it by ' . self::class . '::addTypeMapping',
            );
        }
        return $this->typeMapping[$doctrineType];
    }

    public function getMetadataReader(?ObjectManager $objectManager = null): ?MetadataReaderInterface
    {
        if (
            null !== $objectManager
            && array_key_exists(spl_object_id($objectManager), $this->objectManagerMetadataReaders)) {
            return $this->objectManagerMetadataReaders[spl_object_id($objectManager)];
        }
        return $this->metadataReader;
    }

    public function setMetadataReader(
        MetadataReaderInterface $metadataReader,
        ?ObjectManager $objectManager = null,
    ): void {
        if (null !== $objectManager) {
            $this->objectManagerMetadataReaders[spl_object_id($objectManager)] = $metadataReader;
            return;
        }
        $this->metadataReader = $metadataReader;
    }

    public function isPhpTypeResolverSet(): bool
    {
        return null !== $this->phpTypeResolver;
    }

    public function getPhpTypeResolver(): PhpTypeResolverInterface
    {
        if (null === $this->phpTypeResolver) {
            throw new DoctrineCheckException(
                'PhpTypeResolver missed at config.'
                . ' Set it by ' . self::class . '::setPhpTypeResolver',
            );
        }
        return $this->phpTypeResolver;
    }

    public function setPhpTypeResolver(PhpTypeResolverInterface $phpTypeResolver): void
    {
        $this->phpTypeResolver = $phpTypeResolver;
    }

    public function isCheckNullAtIdFields(): bool
    {
        return $this->checkNullAtIdFields;
    }

    public function setCheckNullAtIdFields(bool $checkNullAtIdFields): void
    {
        $this->checkNullAtIdFields = $checkNullAtIdFields;
    }
}
