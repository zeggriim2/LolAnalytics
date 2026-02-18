<?php

namespace App\Match\Domain\Repository;

use App\Match\Domain\Model\Matche;
use App\Match\Domain\ValueObjet\MatchId;
use App\SharedContext\Domain\Repository\PaginatableRepositoryInterface;
use App\SharedContext\Domain\ValueObjet\Region;

interface MatchRepositoryInterface extends PaginatableRepositoryInterface
{
    public function save(Matche $match, Region $region): void;

    public function exists(MatchId $matchId): bool;

    public function findById(string $matchId): ?Matche;

    /**
     * @return Matche[]
     */
    public function findAll(): array;
}
