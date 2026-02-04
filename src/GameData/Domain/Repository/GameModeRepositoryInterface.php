<?php

declare(strict_types=1);

namespace App\GameData\Domain\Repository;

use App\GameData\Domain\Model\GameMode;

interface GameModeRepositoryInterface
{
    public function save(GameMode $gameMode): void;

    public function findByGameMode(string $gameMode): ?GameMode;

    /**
     * @return GameMode[]
     */
    public function findAll(): array;

    public function deleteAll(): void;
}
