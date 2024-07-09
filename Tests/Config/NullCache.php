<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Config;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class NullCache implements CacheItemPoolInterface
{
    public function getItem(string $key): CacheItemInterface
    {
        return new NullCacheItem();
    }

    public function getItems(array $keys = []): iterable
    {
        return [];
    }

    public function hasItem(string $key): bool
    {
        return false;
    }

    public function clear(): bool
    {
        return true;
    }

    public function deleteItem(string $key): bool
    {
        return true;
    }

    public function deleteItems(array $keys): bool
    {
        return true;
    }

    public function save(CacheItemInterface $item): bool
    {
        return true;
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        return true;
    }

    public function commit(): bool
    {
        return true;
    }
}
