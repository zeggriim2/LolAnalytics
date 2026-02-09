<?php

declare(strict_types=1);

namespace App\Summoner\Domain\Repository;

use App\Summoner\Domain\Model\Summoner;
use App\Summoner\Domain\ValueObject\Puuid;

interface SummonerRepositoryInterface
{
    public function save(Summoner $summoner): void;

    public function findByPuuid(Puuid $puuid): ?Summoner;

    public function exists(Puuid $puuid): bool;
}
