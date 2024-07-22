<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Nighten\DoctrineCheck\Tests\Config\NullCache;

require_once 'vendor/autoload.php';

$doctrineConfig = ORMSetup::createAttributeMetadataConfiguration(
    paths: [
        __DIR__ . '/Entity/Embeddable',
        __DIR__ . '/Entity/ManyToOne',
        __DIR__ . '/Entity/Related',
        __DIR__ . '/Entity/Simple',
    ],
    isDevMode: true,
    cache: new NullCache(),
);
$connection = DriverManager::getConnection([
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite',
], $doctrineConfig);

return new EntityManager($connection, $doctrineConfig);
