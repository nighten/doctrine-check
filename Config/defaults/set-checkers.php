<?php

namespace Nighten\DoctrineCheck\Config\defaults;

use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Check\Checker\FieldMappingChecker;
use Nighten\DoctrineCheck\Check\Checker\AssociationMappingChecker;

return function (DoctrineCheckConfig $config): void {
    $config->setFieldMappingChecker(new FieldMappingChecker());
    $config->setAssociationMappingChecker(new AssociationMappingChecker());
};
