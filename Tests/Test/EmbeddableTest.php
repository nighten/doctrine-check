<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Test;

use Nighten\DoctrineCheck\Check\CheckTypes;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Tests\BaseTestCase;
use Nighten\DoctrineCheck\Tests\Entity\Embeddable\EntityWithEmbedded;
use Nighten\DoctrineCheck\Tests\Entity\Embeddable\EntityWithEmbeddedWithNullErrors;
use Nighten\DoctrineCheck\Tests\Entity\Embeddable\EntityWithEmbeddedWithTypeErrors;

class EmbeddableTest extends BaseTestCase
{
    /**
     * @throws DoctrineCheckException
     */
    public function testOne(): void
    {
        $config = $this->getConfig();
        $config->addEntityClass(EntityWithEmbedded::class);
        $result = (new CheckTypes())->check($config);
        $this->assertEquals(7, $result->getProcessedFieldsCount(), 'check ProcessedFieldsCount');
        $this->assertEquals(0, $result->getSkippedCount(), 'check SkippedCount');
        $this->assertFalse($result->hasErrors(), 'check errors');
    }

    /**
     * @throws DoctrineCheckException
     */
    public function testNegativeTypes(): void
    {
        $errors = [
            EntityWithEmbeddedWithTypeErrors::class . ':embedded2' => [
                'message' => 'Embeddable type "Nighten\DoctrineCheck\Tests\Entity\Embeddable\Embeddable2Class" not accepted php type(s): Nighten\DoctrineCheck\Tests\Entity\Embeddable\Embeddable1Class',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntityWithEmbeddedWithTypeErrors::class . ':embedded3.integer' => [
                'message' => 'Doctrine type "string" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntityWithEmbeddedWithTypeErrors::class . ':embedded3.boolean' => [
                'message' => 'Doctrine type "integer" not accepted php type(s): bool',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
        ];

        $config = $this->getConfig();
        $config->addEntityClass(EntityWithEmbeddedWithTypeErrors::class);
        $result = (new CheckTypes())->check($config);
        $this->assertEquals(7, $result->getProcessedFieldsCount(), 'check ProcessedFieldsCount');
        $this->assertEquals(0, $result->getSkippedCount(), 'check SkippedCount');
        $this->assertTrue($result->hasErrors(), 'check errors');
        $this->assertEquals($errors, $result->getAllErrors(), 'check errors titles');
    }

    /**
     * @throws DoctrineCheckException
     */
    public function testNegativeNulls(): void
    {
        $errors = [
            EntityWithEmbeddedWithNullErrors::class . ':embedded1' => [
                'message' => 'Embeddable type Nighten\DoctrineCheck\Tests\Entity\Embeddable\Embeddable1Class map on nullable php field',
                'type' => 'WRONG_NULLABLE',
            ],
            EntityWithEmbeddedWithNullErrors::class . ':embedded2.integer' => [
                'message' => 'Doctrine type not nullable but in php allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntityWithEmbeddedWithNullErrors::class . ':embedded2.boolean' => [
                'message' => 'Doctrine type nullable but in php not allow null',
                'type' => 'WRONG_NULLABLE',
            ],
        ];

        $config = $this->getConfig();
        $config->addEntityClass(EntityWithEmbeddedWithNullErrors::class);
        $result = (new CheckTypes())->check($config);
        $this->assertEquals(7, $result->getProcessedFieldsCount(), 'check ProcessedFieldsCount');
        $this->assertEquals(0, $result->getSkippedCount(), 'check SkippedCount');
        $this->assertTrue($result->hasErrors(), 'check errors');
        $this->assertEquals($errors, $result->getAllErrors(), 'check errors titles');
    }
}
