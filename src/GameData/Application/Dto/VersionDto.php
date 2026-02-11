<?php

declare(strict_types=1);

namespace App\GameData\Application\Dto;

final readonly class VersionDto
{
    public function __construct(
        public string $version,
    ) {
    }

    public static function fromString(string $version): self
    {
        return new self(
            $version,
        );
    }
}
