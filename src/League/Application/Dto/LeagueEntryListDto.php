<?php

declare(strict_types=1);

namespace App\League\Application\Dto;

use App\League\Domain\Model\LeagueEntry;

final readonly class LeagueEntryListDto
{
    public function __construct(
        public int $rank,
        public string $puuid,
        public int $leaguePoints,
        public int $wins,
        public int $losses,
        public int $winRate,
        public bool $hotStreak,
        public bool $veteran,
        public bool $freshBlood,
    ) {
    }

    public static function fromDomain(LeagueEntry $entry, int $rank): self
    {
        $total = $entry->wins() + $entry->losses();
        $winRate = $total > 0 ? (int) round($entry->wins() / $total * 100) : 0;

        return new self(
            rank: $rank,
            puuid: $entry->puuid(),
            leaguePoints: $entry->leaguePoints(),
            wins: $entry->wins(),
            losses: $entry->losses(),
            winRate: $winRate,
            hotStreak: $entry->hotStreak(),
            veteran: $entry->veteran(),
            freshBlood: $entry->freshBlood(),
        );
    }
}
