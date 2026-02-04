<?php

declare(strict_types=1);

namespace App\GameData\Domain\Repository;

use App\GameData\Domain\Model\GameType;

interface GameTypeRepositoryInterface
{
    public function save(GameType $gameType): void;

    public function findByGameType(string $gameType): ?GameType;

    /**
     * @return GameType[]
     */
    public function findAll(): array;

    public function deleteAll(): void;
}
