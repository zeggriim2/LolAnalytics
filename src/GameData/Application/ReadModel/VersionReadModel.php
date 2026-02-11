<?php

declare(strict_types=1);

namespace App\GameData\Application\ReadModel;

use App\GameData\Domain\Model\Version;

final readonly class VersionReadModel
{
    public function __construct(
        public string $version,
    ) {
    }

    public static function fromDomain(Version $version): self
    {
        return new self(
            version: $version->version(),
        );
    }
}
