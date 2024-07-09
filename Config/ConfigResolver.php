<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Config;

use Closure;
use Nighten\DoctrineCheck\Doctrine\DefaultMetadataReader;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;

class ConfigResolver
{
    /**
     * @throws DoctrineCheckException
     */
    public function resolve(string | Closure $userConfiguration = 'doctrine-check-config.php'): DoctrineCheckConfig
    {
        $config = new DoctrineCheckConfig();
        $this->setDefaults($config);
        if (is_string($userConfiguration)) {
            $configFileName = getcwd() . DIRECTORY_SEPARATOR . $userConfiguration;
            $userConfig = require $configFileName;
        } else {
            $userConfig = $userConfiguration;
        }
        $userConfig($config);
        $this->setMetadataReader($config);
        return $config;
    }

    /**
     * @throws DoctrineCheckException
     */
    private function setDefaults(DoctrineCheckConfig $config): void
    {
        $dir = __DIR__ . DIRECTORY_SEPARATOR . 'defaults';
        $res = opendir($dir);
        if (false === $res) {
            throw new DoctrineCheckException('Filed open directory: "' . $dir . '"');
        }
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
