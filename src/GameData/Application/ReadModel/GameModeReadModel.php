<?php

declare(strict_types=1);

namespace App\GameData\Application\ReadModel;

use App\GameData\Domain\Model\GameMode;

final readonly class GameModeReadModel
{
    public function __construct(
        public string $gameMode,
        public string $description,
    ) {
    }

    public static function fromDomain(GameMode $gameMode): self
    {
        return new self(
            gameMode: $gameMode->gameMode(),
            description: $gameMode->description(),
        );
    }
}
