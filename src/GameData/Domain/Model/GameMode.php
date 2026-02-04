<?php

declare(strict_types=1);

namespace App\GameData\Domain\Model;

final class GameMode
{
    public function __construct(
        private readonly string $gameMode,
        private readonly string $description,
    ) {
    }

    public function gameMode(): string
    {
        return $this->gameMode;
    }

    public function description(): string
    {
        return $this->description;
    }
}
