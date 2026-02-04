<?php

declare(strict_types=1);

namespace App\GameData\Domain\Model;

final class Map
{
    public function __construct(
        private readonly int $mapId,
        private readonly string $mapName,
        private readonly ?string $notes,
    ) {
    }

    public function mapId(): int
    {
        return $this->mapId;
    }

    public function mapName(): string
    {
        return $this->mapName;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }
}
