<?php

namespace Nighten\DoctrineCheck\Config\defaults;

use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use DateTime;
use DateTimeImmutable;

return function (DoctrineCheckConfig $config): void {
    $mapTypes = [
        'integer' => 'int',
        'smallint' => 'int',
        'bigint' => 'string',
        'string' => 'string',
        'text' => 'string',
        'decimal' => 'string',
        'float' => 'float',
        'boolean' => 'bool',
        'datetime' => DateTime::class,
        'datetime_immutable' => DateTimeImmutable::class,
        'date' => DateTime::class,
        'date_immutable' => DateTimeImmutable::class,
        'guid' => 'string',
        'simple_array' => 'array',
        'json' => 'array',
        'json_object' => 'array',
        'serialized' => 'array',
    ];
    foreach ($mapTypes as $name => $type) {
        $config->addTypeMapping($name, $type);
    }
};
