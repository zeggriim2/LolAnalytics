<?php

declare(strict_types=1);

namespace App\GameData\Domain\Repository;

use App\GameData\Domain\Model\Season;

interface SeasonRepositoryInterface
{
    public function save(Season $season): void;

    public function findById(int $id): ?Season;

    /**
     * @return Season[]
     */
    public function findAll(): array;

    public function deleteAll(): void;
}
