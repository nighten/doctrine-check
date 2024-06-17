<?php

declare(strict_types=1);

namespace Nighten\DoctrineCheck\Console;

class ConsoleConfiguration
{
    private bool $hideIgnores = false;
    private bool $doNotFailOnUselessIgnore = false;
    private bool $showSkipped = false;

    public function isHideIgnores(): bool
    {
        return $this->hideIgnores;
    }

    public function setHideIgnores(bool $hideIgnores): void
    {
        $this->hideIgnores = $hideIgnores;
    }

    public function isDoNotFailOnUselessIgnore(): bool
    {
        return $this->doNotFailOnUselessIgnore;
    }

    public function setDoNotFailOnUselessIgnore(bool $doNotFailOnUselessIgnore): void
    {
        $this->doNotFailOnUselessIgnore = $doNotFailOnUselessIgnore;
    }

    public function isShowSkipped(): bool
    {
        return $this->showSkipped;
    }

    public function setShowSkipped(bool $showSkipped): void
    {
        $this->showSkipped = $showSkipped;
    }
}
