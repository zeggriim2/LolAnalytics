<?php

declare(strict_types=1);

namespace App\GameData\Domain\Model;

final class GameType
{
    public function __construct(
        private readonly string $gameType,
        private readonly string $description,
    ) {
    }

    public function gameType(): string
    {
        return $this->gameType;
    }

    public function description(): string
    {
        return $this->description;
    }
}
