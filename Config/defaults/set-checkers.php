<?php

namespace Nighten\DoctrineCheck\Config\defaults;

use Nighten\DoctrineCheck\Check\Checker\EmbeddedMappingChecker;
use Nighten\DoctrineCheck\Config\DoctrineCheckConfig;
use Nighten\DoctrineCheck\Check\Checker\FieldMappingChecker;
use Nighten\DoctrineCheck\Check\Checker\AssociationMappingChecker;

return function (DoctrineCheckConfig $config): void {
    $config->setFieldMappingChecker(new FieldMappingChecker());
    $config->setEmbeddedMappingChecker(new EmbeddedMappingChecker());
    $config->setAssociationMappingChecker(new AssociationMappingChecker());
};
