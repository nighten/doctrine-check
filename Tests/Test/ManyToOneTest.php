<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Test;

use Nighten\DoctrineCheck\Check\CheckTypes;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Tests\BaseTestCase;
use Nighten\DoctrineCheck\Tests\Entity\ManyToOne\EntityWithManyToOne;
use Nighten\DoctrineCheck\Tests\Entity\ManyToOne\EntityWithManyToOneWithNullError;
use Nighten\DoctrineCheck\Tests\Entity\ManyToOne\EntityWithManyToOneWithPHPDoc;
use Nighten\DoctrineCheck\Tests\Entity\ManyToOne\EntityWithManyToOneWithTypeError;

class ManyToOneTest extends BaseTestCase
{
    /**
     * @throws DoctrineCheckException
     */
    public function testPositiveWithManyToOnePhpType(): void
    {
        $config = $this->getConfig();
        $config->addEntityClass(EntityWithManyToOne::class);
        $result = (new CheckTypes())->check($config);
        $this->assertEquals(2, $result->getProcessedFieldsCount(), 'check ProcessedFieldsCount');
        $this->assertEquals(0, $result->getSkippedCount(), 'check SkippedCount');
        $this->assertFalse($result->hasErrors(), 'check errors');
    }

    /**
     * @throws DoctrineCheckException
     */
    public function testPositiveWithManyToOnePhpDocType(): void
    {
        $config = $this->getConfig();
        $config->addEntityClass(EntityWithManyToOneWithPHPDoc::class);
        $result = (new CheckTypes())->check($config);
        $this->assertEquals(3, $result->getProcessedFieldsCount(), 'check ProcessedFieldsCount');
        $this->assertEquals(1, $result->getSkippedCount(), 'check SkippedCount');
        $this->assertFalse($result->hasErrors(), 'check errors');
    }

    /**
     * @throws DoctrineCheckException
     */
    public function testNegativeWithManyToOneWrongType(): void
    {
        $errors = [
            EntityWithManyToOneWithTypeError::class . ':related' => [
                'message' => 'Doctrine type "Nighten\DoctrineCheck\Tests\Entity\Related\EntityRelated" not matched with php type: "Nighten\DoctrineCheck\Tests\Entity\Related\EntityRelated2"',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
        ];
        $config = $this->getConfig();
        $config->addEntityClass(EntityWithManyToOneWithTypeError::class);
        $result = (new CheckTypes())->check($config);
        $this->assertEquals(2, $result->getProcessedFieldsCount(), 'check ProcessedFieldsCount');
        $this->assertEquals(0, $result->getSkippedCount(), 'check SkippedCount');
        $this->assertTrue($result->hasErrors(), 'check errors');
        $this->assertEquals($errors, $result->getAllErrors(), 'check errors titles');
    }

    /**
     * @throws DoctrineCheckException
     */
    public function testNegativeWithManyToOneWrongNull(): void
    {
        $errors = [
            EntityWithManyToOneWithNullError::class . ':related' => [
                'message' => 'Doctrine type nullable but in php not allow null',
                'type' => 'WRONG_NULLABLE',
            ],
        ];
        $config = $this->getConfig();
        $config->addEntityClass(EntityWithManyToOneWithNullError::class);
        $result = (new CheckTypes())->check($config);
        $this->assertEquals(2, $result->getProcessedFieldsCount(), 'check ProcessedFieldsCount');
        $this->assertEquals(0, $result->getSkippedCount(), 'check SkippedCount');
        $this->assertTrue($result->hasErrors(), 'check errors');
        $this->assertEquals($errors, $result->getAllErrors(), 'check errors titles');
    }
}
