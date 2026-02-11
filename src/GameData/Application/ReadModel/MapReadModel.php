<?php

declare(strict_types=1);

namespace App\GameData\Application\ReadModel;

use App\GameData\Domain\Model\Map;

final readonly class MapReadModel
{
    public function __construct(
        public int $mapId,
        public string $mapName,
        public ?string $notes,
    ) {
    }

    public static function fromDomain(Map $map): self
    {
        return new self(
            mapId: $map->mapId(),
            mapName: $map->mapName(),
            notes: $map->notes(),
        );
    }
}
