<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Console;

class ConsoleConfiguration
{
    private bool $hideIgnores = false;
    private bool $doNotFailOnUslessIgnore = false;

    public function isHideIgnores(): bool
    {
        return $this->hideIgnores;
    }

    public function setHideIgnores(bool $hideIgnores): void
    {
        $this->hideIgnores = $hideIgnores;
    }

    public function isDoNotFailOnUslessIgnore(): bool
    {
        return $this->doNotFailOnUslessIgnore;
    }

    public function setDoNotFailOnUslessIgnore(bool $doNotFailOnUslessIgnore): void
    {
        $this->doNotFailOnUslessIgnore = $doNotFailOnUslessIgnore;
    }
}
