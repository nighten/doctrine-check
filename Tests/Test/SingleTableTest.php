<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Test;

use Nighten\DoctrineCheck\Check\CheckTypes;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Tests\BaseTestCase;
use Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SingleTable\BaseEntityWithError;
use Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SingleTable\ExtendedEntity1WithError;
use Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SingleTable\ExtendedEntity2;
use Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SingleTable\ExtendedEntity1;
use Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SingleTable\BaseEntity;
use Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SuperClass\EntityWithSuperclassWithError;
use Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SuperClass\SuperclassWithError;

class SingleTableTest extends BaseTestCase
{
    /**
     * @throws DoctrineCheckException
     */
    public function testPositive(): void
    {
        $config = $this->getConfig();
        $config->addEntityClass(BaseEntity::class);
        $config->addEntityClass(ExtendedEntity1::class);
        $config->addEntityClass(ExtendedEntity2::class);
        $result = (new CheckTypes())->check($config);
        $this->assertEquals(13, $result->getProcessedFieldsCount(), 'check ProcessedFieldsCount');
        $this->assertEquals(0, $result->getSkippedCount(), 'check SkippedCount');
        $this->assertFalse($result->hasErrors(), 'check errors');
    }

    /**
     * @throws DoctrineCheckException
     */
    public function testWithErrors(): void
    {
        $errors = [
            BaseEntityWithError::class . ':field1' => [
                'message' => 'Doctrine type "string" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            BaseEntityWithError::class . ':field2' => [
                'message' => 'Doctrine type not nullable but in php allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            ExtendedEntity1WithError::class . ':field1' => [
                'message' => 'Doctrine type "string" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            ExtendedEntity1WithError::class . ':field2' => [
                'message' => 'Doctrine type not nullable but in php allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            ExtendedEntity1WithError::class . ':exField2' => [
                'message' => 'Doctrine type "integer" not accepted php type(s): string',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
        ];

        $config = $this->getConfig();
        $config->addEntityClass(BaseEntityWithError::class);
        $config->addEntityClass(ExtendedEntity1WithError::class);
        $result = (new CheckTypes())->check($config);
        $this->assertEquals(8, $result->getProcessedFieldsCount(), 'check ProcessedFieldsCount');
        $this->assertEquals(0, $result->getSkippedCount(), 'check SkippedCount');
        $this->assertTrue($result->hasErrors(), 'check errors');
        $this->assertEquals($errors, $result->getAllErrors(), 'check errors titles');
    }
}
