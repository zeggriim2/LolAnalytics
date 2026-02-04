<?php

declare(strict_types=1);

namespace App\GameData\Application\Dto;

final readonly class MapDto
{
    public function __construct(
        public int $mapId,
        public string $mapName,
        public ?string $notes,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['mapId'],
            $data['mapName'],
            $data['notes'] ?? null,
        );
    }
}
