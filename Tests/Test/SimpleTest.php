<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Test;

use Entity\EntitySimpleType;
use Entity\EntitySimpleWithNullErrors;
use Entity\EntitySimpleWithTypeErrors;
use Nighten\DoctrineCheck\Check\CheckTypes;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use Nighten\DoctrineCheck\Tests\BaseTestCase;

class SimpleTest extends BaseTestCase
{
    /**
     * @throws DoctrineCheckException
     */
    public function testPositive(): void
    {
        $config = $this->getConfig();
        $config->addEntityClass(EntitySimpleType::class);
        $result = (new CheckTypes())->check($config);
        $this->assertFalse($result->hasErrors());
    }

    /**
     * @throws DoctrineCheckException
     */
    public function testNegativeTypes(): void
    {
        $errors = [
            EntitySimpleWithTypeErrors::class . ':integer' => [
                'message' => 'Doctrine type "integer" not accepted php type(s): string',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':smallint' => [
                'message' => 'Doctrine type "smallint" not accepted php type(s): string',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':bigint' => [
                'message' => 'Doctrine type "bigint" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':string' => [
                'message' => 'Doctrine type "string" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':text' => [
                'message' => 'Doctrine type "text" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':decimal' => [
                'message' => 'Doctrine type "decimal" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':float' => [
                'message' => 'Doctrine type "float" not accepted php type(s): string',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':boolean' => [
                'message' => 'Doctrine type "boolean" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':datetime' => [
                'message' => 'Doctrine type "datetime" not accepted php type(s): DateTimeImmutable',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':datetime_immutable' => [
                'message' => 'Doctrine type "datetime_immutable" not accepted php type(s): DateTime',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':date' => [
                'message' => 'Doctrine type "date" not accepted php type(s): DateTimeImmutable',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':date_immutable' => [
                'message' => 'Doctrine type "date_immutable" not accepted php type(s): DateTime',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':guid' => [
                'message' => 'Doctrine type "guid" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':simple_array' => [
                'message' => 'Doctrine type "simple_array" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':json' => [
                'message' => 'Doctrine type "json" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':json_object' => [
                'message' => 'Doctrine type "json_object" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
            EntitySimpleWithTypeErrors::class . ':serialized' => [
                'message' => 'Doctrine type "serialized" not accepted php type(s): int',
                'type' => 'WRONG_MAPPING_TYPE',
            ],
        ];

        $config = $this->getConfig();
        $config->addEntityClass(EntitySimpleWithTypeErrors::class);
        $results = (new CheckTypes())->check($config);
        $this->assertTrue($results->hasErrors());
        $this->assertEquals($errors, $results->getAllErrors());
    }

    /**
     * @throws DoctrineCheckException
     */
    public function testNegativeNulls(): void
    {
        $errors = [
            EntitySimpleWithNullErrors::class . ':bigint' => [
                'message' => 'Doctrine type nullable but in php not allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':string' => [
                'message' => 'Doctrine type not nullable but in php allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':text' => [
                'message' => 'Doctrine type nullable but in php not allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':decimal' => [
                'message' => 'Doctrine type not nullable but in php allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':float' => [
                'message' => 'Doctrine type nullable but in php not allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':boolean' => [
                'message' => 'Doctrine type not nullable but in php allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':datetime' => [
                'message' => 'Doctrine type nullable but in php not allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':datetime_immutable' => [
                'message' => 'Doctrine type not nullable but in php allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':date' => [
                'message' => 'Doctrine type nullable but in php not allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':date_immutable' => [
                'message' => 'Doctrine type not nullable but in php allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':guid' => [
                'message' => 'Doctrine type nullable but in php not allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':simple_array' => [
                'message' => 'Doctrine type not nullable but in php allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':json' => [
                'message' => 'Doctrine type nullable but in php not allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':json_object' => [
                'message' => 'Doctrine type not nullable but in php allow null',
                'type' => 'WRONG_NULLABLE',
            ],
            EntitySimpleWithNullErrors::class . ':serialized' => [
                'message' => 'Doctrine type nullable but in php not allow null',
                'type' => 'WRONG_NULLABLE',
            ],
        ];

        $config = $this->getConfig();
        $config->addEntityClass(EntitySimpleWithNullErrors::class);
        $results = (new CheckTypes())->check($config);
        $this->assertTrue($results->hasErrors());
        $this->assertEquals($errors, $results->getAllErrors());
    }
}
