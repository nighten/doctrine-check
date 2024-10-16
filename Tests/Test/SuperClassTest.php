<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Test;

use Nighten\DoctrineCheck\Check\CheckTypes;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Tests\BaseTestCase;
use Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SuperClass\EntityWithSuperclass;
use Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SuperClass\EntityWithSuperclassWithError;
use Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SuperClass\Superclass;
use Nighten\DoctrineCheck\Tests\Entity\InheritanceMapping\SuperClass\SuperclassWithError;

class SuperClassTest extends BaseTestCase
{
    /**
     * @throws DoctrineCheckException
     */
    public function testPositive(): void
    {
        $config = $this->getConfig();
        $config->addEntityClass(Superclass::class);
        $config->addEntityClass(EntityWithSuperclass::class);
        $result = (new CheckTypes())->check($config);
        $this->assertEquals(8, $result->getProcessedFieldsCount(), 'check ProcessedFieldsCount');
        $this->assertEquals(0, $result->getSkippedCount(), 'check SkippedCount');
        $this->assertFalse($result->hasErrors(), 'check errors');
    }

    /**
     * @throws DoctrineCheckException
     */
    public function testWithError(): void
    {
        $errors = [
            SuperclassWithError::class . ':mapped2' => [
                'message' => 'Doctrine type "integer" not accepted php type(s): string',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntityWithSuperclassWithError::class . ':name' => [
                'message' => 'Doctrine type "integer" not accepted php type(s): string',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
        ];

        $config = $this->getConfig();
        $config->addEntityClass(SuperclassWithError::class);
        $config->addEntityClass(EntityWithSuperclassWithError::class);
        $result = (new CheckTypes())->check($config);
        $this->assertEquals(8, $result->getProcessedFieldsCount(), 'check ProcessedFieldsCount');
        $this->assertEquals(0, $result->getSkippedCount(), 'check SkippedCount');
        $this->assertTrue($result->hasErrors(), 'check errors');
        $this->assertEquals($errors, $result->getAllErrors(), 'check errors titles');
    }
}
