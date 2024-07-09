<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Tests\Config;

use Psr\Cache\CacheItemInterface;

class NullCacheItem implements CacheItemInterface
{
    public function getKey(): string
    {
        return '';
    }

    public function get(): mixed
    {
        return '';
    }

    public function isHit(): bool
    {
        return false;
    }

    public function set(mixed $value): static
    {
        return $this;
    }

    public function expiresAt(?\DateTimeInterface $expiration): static
    {
        return $this;
    }

    public function expiresAfter(\DateInterval | int | null $time): static
    {
        return $this;
    }
}
