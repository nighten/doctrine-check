<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Provider;

use Composer\InstalledVersions;
use Nighten\DoctrineCheck\Check\Contract\PackageVersionProviderInterface;
use Nighten\DoctrineCheck\Exception\DoctrineCheckException;

class PackageVersionProvider implements PackageVersionProviderInterface
{
    public function hasPackage(string $package): bool
    {
        return InstalledVersions::isInstalled($package);
    }

    /**
     * @throws DoctrineCheckException
     */
    public function getVersion(string $package): string
    {
        if (!$this->hasPackage($package)) {
            throw new DoctrineCheckException('package ' . $package . ' does not exist.');
        }
        return InstalledVersions::getPrettyVersion($package);
    }
}
