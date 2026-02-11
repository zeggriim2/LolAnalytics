<?php

declare(strict_types=1);

namespace App\GameData\Domain\Model;

final class Version
{
    public function __construct(
        private readonly string $version,
    ) {
    }

    public function version(): string
    {
        return $this->version;
    }
}
