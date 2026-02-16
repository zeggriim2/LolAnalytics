<?php

declare(strict_types=1);

namespace App\Champion\Domain\Repository;

use App\Champion\Domain\Model\Champion;

interface ChampionRepositoryInterface
{
    public function save(Champion $champion): void;

    public function findByRiotIdAndVersion(string $riotId, string $version): ?Champion;

    public function findByRiotId(string $riotId): ?Champion;

    /**
     * @return Champion[]
     */
    public function findAll(): array;

    /**
     * @return Champion[]
     */
    public function findByVersion(string $version): array;
}
