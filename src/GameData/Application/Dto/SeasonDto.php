<?php

declare(strict_types=1);

namespace App\GameData\Application\Dto;

final readonly class SeasonDto
{
    public function __construct(
        public int $id,
        public string $season,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['season'],
        );
    }
}
