<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Config;

use Nighten\DoctrineCheck\Doctrine\DefaultMetadataReader;

class ConfigResolver
{
    public function resolve(string $fileName = 'doctrine-check-config.php'): DoctrineCheckConfig
    {
        $config = new DoctrineCheckConfig();
        $this->setDefaults($config);
        $configFileName = getcwd() . DIRECTORY_SEPARATOR . $fileName;
        $userConfig = require $configFileName;
        $userConfig($config);
        $this->setMetadataReader($config);
        return $config;
    }

    private function setDefaults(DoctrineCheckConfig $config): void
    {
        $dir = __DIR__ . DIRECTORY_SEPARATOR . 'defaults';
        $res = opendir($dir);
        while (($file = readdir($res)) !== false) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $configSet = require $dir . DIRECTORY_SEPARATOR . $file;
                $configSet($config);
            }
        }
        closedir($res);
    }

    private function setMetadataReader(DoctrineCheckConfig $config): void
    {
        if (null !== $config->getMetadataReader()) {
            return;
        }
        foreach ($config->getObjectManagers() as $objectManager) {
            if (null === $config->getMetadataReader($objectManager)) {
                $config->setMetadataReader(new DefaultMetadataReader());
            }
        }
    }
}
