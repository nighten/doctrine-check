<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Nighten\DoctrineCheck\Tests\Config\NullCache;

require_once 'vendor/autoload.php';

$doctrineConfig = ORMSetup::createAttributeMetadataConfiguration(
    paths: array(__DIR__ . '/Entity'),
    isDevMode: true,
    cache: new NullCache(),
);
$connection = DriverManager::getConnection([
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite',
], $doctrineConfig);

return new EntityManager($connection, $doctrineConfig);
