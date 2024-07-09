<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests;

use Nighten\DoctrineCheck\Config\ConfigResolver;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * @throws DoctrineCheckException
     */
    protected function getConfig(): DoctrineCheckConfig
    {
        $configResolver = new ConfigResolver();
        $f = function (DoctrineCheckConfig $config): void {
            $objectManager = require 'bootstrap.php';
            $config->addObjectManager($objectManager);
        };
        return $configResolver->resolve($f);
    }
}
