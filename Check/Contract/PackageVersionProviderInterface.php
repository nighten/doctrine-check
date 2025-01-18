<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Check\Contract;

interface PackageVersionProviderInterface
{
    public function hasPackage(string $package): bool;

    public function getVersion(string $package): string;
}
