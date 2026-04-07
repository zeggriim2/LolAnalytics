<?php

declare(strict_types=1);

namespace App\League\Domain\Model;

final class LeagueEntry
{
    private function __construct(
        private readonly string $puuid,
        private readonly string $summonerId,
        private readonly int $leaguePoints,
        private readonly int $wins,
        private readonly int $losses,
        private readonly ?string $rank,
        private readonly bool $hotStreak,
        private readonly bool $veteran,
        private readonly bool $freshBlood,
    ) {
    }

    public static function create(
        string $puuid,
        string $summonerId,
        int $leaguePoints,
        int $wins,
        int $losses,
        ?string $rank,
        bool $hotStreak,
        bool $veteran,
        bool $freshBlood,
    ): self {
        return new self($puuid, $summonerId, $leaguePoints, $wins, $losses, $rank, $hotStreak, $veteran, $freshBlood);
    }

    public function puuid(): string
    {
        return $this->puuid;
    }

    public function summonerId(): string
    {
        return $this->summonerId;
    }

    public function leaguePoints(): int
    {
        return $this->leaguePoints;
    }

    public function wins(): int
    {
        return $this->wins;
    }

    public function losses(): int
    {
        return $this->losses;
    }

    public function rank(): ?string
    {
        return $this->rank;
    }

    public function hotStreak(): bool
    {
        return $this->hotStreak;
    }

    public function veteran(): bool
    {
        return $this->veteran;
    }

    public function freshBlood(): bool
    {
        return $this->freshBlood;
    }
}
