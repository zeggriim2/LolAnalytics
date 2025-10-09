<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Repository;

use App\Match\Domain\Model\Matche;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Domain\ValueObjet\MatchId;

final class InMemoryMatchRepository implements MatchRepositoryInterface
{
    /** @var Matche[][] */
    private array $matchesByRegion = [];

    public function save(Matche $match, string $region): void
    {
        $this->matchesByRegion[$region][(string) $match->id()] = $match;
    }

    public function exists(MatchId $matchId): bool
    {
        $id = (string) $matchId;

        foreach ($this->matchesByRegion as $regionMatches) {
            if (isset($regionMatches[$id])) {
                return true;
            }
        }

        return false;
    }

    public function findById(string $matchId): ?Matche
    {
        foreach ($this->matchesByRegion as $regionMatches) {
            if (isset($regionMatches[$matchId])) {
                return $regionMatches[$matchId];
            }
        }

        return null;
    }

    public function findAll(): array
    {
        $all = [];

        foreach ($this->matchesByRegion as $regionMatches) {
            $all = array_merge($all, $regionMatches);
        }

        return $all;
    }
}
