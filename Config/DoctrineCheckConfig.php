<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Config;

use Doctrine\Persistence\ObjectManager;
use Nighten\DoctrineCheck\Check\Contract\AssociationMappingCheckerInterface;
use Nighten\DoctrineCheck\Check\Contract\FieldMappingCheckerInterface;
use Nighten\DoctrineCheck\Console\ConsoleInputConfigurationFactoryInterface;
use Nighten\DoctrineCheck\Doctrine\MetadataReaderInterface;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Php\PhpTypeResolverInterface;
use Nighten\DoctrineCheck\Ignore\IgnoreStorage;

class DoctrineCheckConfig
{
    /** @var array<string, string[]> */
    private array $typeMapping = [];

    private IgnoreStorage $ignores;

    /** @var array<int, ObjectManager> */
    private array $objectManagers = [];

    /** @var array<int, MetadataReaderInterface> */
    private array $objectManagerMetadataReaders = [];

    /** @var array<class-string, class-string> */
    private array $entityClasses = [];

    /** @var array<class-string, class-string> */
    private array $excludedEntityClasses = [];

    private ?MetadataReaderInterface $metadataReader = null;

    private ?PhpTypeResolverInterface $phpTypeResolver = null;

    private ?FieldMappingCheckerInterface $fieldMappingChecker = null;

    private ?AssociationMappingCheckerInterface $associationMappingChecker = null;

    private ?ConsoleInputConfigurationFactoryInterface $consoleInputConfigurationFactory = null;

    private bool $checkNullAtIdFields = true;

    public function __construct()
    {
        $this->ignores = new IgnoreStorage();
    }

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

    public function getIgnoreStorage(): IgnoreStorage
    {
        return $this->ignores;
    }

    /**
     * @param class-string $className
     */
    public function addIgnore(string $className, string $field, string $errorType): void
    {
        $this->ignores->addIgnore($className, $field, $errorType);
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

    public function getFieldMappingChecker(): ?FieldMappingCheckerInterface
    {
        return $this->fieldMappingChecker;
    }

    public function setFieldMappingChecker(?FieldMappingCheckerInterface $fieldMappingChecker): void
    {
        $this->fieldMappingChecker = $fieldMappingChecker;
    }

    public function getAssociationMappingChecker(): ?AssociationMappingCheckerInterface
    {
        return $this->associationMappingChecker;
    }

    public function setAssociationMappingChecker(?AssociationMappingCheckerInterface $associationMappingChecker): void
    {
        $this->associationMappingChecker = $associationMappingChecker;
    }

    public function setCheckNullAtIdFields(bool $checkNullAtIdFields): void
    {
        $this->checkNullAtIdFields = $checkNullAtIdFields;
    }

    public function getConsoleInputConfigurationFactory(): ?ConsoleInputConfigurationFactoryInterface
    {
        return $this->consoleInputConfigurationFactory;
    }

    public function setConsoleInputConfigurationFactory(
        ConsoleInputConfigurationFactoryInterface $consoleInputConfigurationFactory
    ): void {
        $this->consoleInputConfigurationFactory = $consoleInputConfigurationFactory;
    }

    /**
     * @return array<class-string, class-string>
     */
    public function getEntityClasses(): array
    {
        return $this->entityClasses;
    }

    /**
     * @param class-string $entityClass
     */
    public function addEntityClass(string $entityClass): void
    {
        $this->entityClasses[$entityClass] = $entityClass;
    }

    public function hasEntityClasses(): bool
    {
        return count($this->entityClasses) > 0;
    }

    public function existEntityClasses(string $entityClass): bool
    {
        return array_key_exists($entityClass, $this->entityClasses);
    }

    /**
     * @return array<class-string, class-string>
     */
    public function getExcludedEntityClasses(): array
    {
        return $this->excludedEntityClasses;
    }

    /**
     * @param class-string $entityClass
     */
    public function addExcludedEntityClasses(string $entityClass): void
    {
        $this->excludedEntityClasses[$entityClass] = $entityClass;
    }

    public function hasExcludedEntityClasses(): bool
    {
        return count($this->entityClasses) > 0;
    }

    public function existExcludedEntityClasses(string $entityClass): bool
    {
        return array_key_exists($entityClass, $this->excludedEntityClasses);
    }
}
