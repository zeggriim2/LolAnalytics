<?php

declare(strict_types=1);

namespace App\GameData\Application\Dto;

final readonly class GameTypeDto
{
    public function __construct(
        public string $gameType,
        public string $description,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['gametype'],
            $data['description'] ?? '',
        );
    }
}
