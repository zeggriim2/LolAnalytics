<?php

declare(strict_types=1);

namespace App\GameData\Application\ReadModel;

use App\GameData\Domain\Model\GameType;

final readonly class GameTypeReadModel
{
    public function __construct(
        public string $gameType,
        public string $description,
    ) {
    }

    public static function fromDomain(GameType $gameType): self
    {
        return new self(
            gameType: $gameType->gameType(),
            description: $gameType->description(),
        );
    }
}
