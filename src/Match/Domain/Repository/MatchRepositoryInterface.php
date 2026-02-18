<?php

namespace App\Match\Domain\Repository;

use App\Match\Domain\Model\Matche;
use App\Match\Domain\ValueObjet\MatchId;
use App\SharedContext\Domain\ValueObjet\Region;

interface MatchRepositoryInterface
{
    public function save(Matche $match, Region $region): void;

    public function exists(MatchId $matchId): bool;

    public function findById(string $matchId): ?Matche;

    /**
     * @return Matche[]
     */
    public function findAll(): array;

    /**
     * @return Matche[]
     */
    public function findPaginated(int $offset, int $limit): array;

    public function count(): int;
}
