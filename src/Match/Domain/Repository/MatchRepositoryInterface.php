<?php

namespace App\Match\Domain\Repository;

use App\Match\Domain\Model\Matche;
use App\Match\Domain\ValueObjet\MatchId;

interface MatchRepositoryInterface
{
    public function save(Matche $match, string $region): void;

    public function exists(MatchId $matchId): bool;

    public function findById(string $matchId): ?Matche;

    public function findAll(): array;
}
