<?php

declare(strict_types=1);

namespace App\League\Application\Dto;

use App\League\Domain\Model\LeagueEntry;
use App\Summoner\Domain\Model\Summoner;

final readonly class LeagueEntryListDto
{
    public function __construct(
        public int $rank,
        public string $puuid,
        public ?string $gameName,
        public ?string $tagLine,
        public int $leaguePoints,
        public int $wins,
        public int $losses,
        public int $winRate,
        public bool $hotStreak,
        public bool $veteran,
        public bool $freshBlood,
    ) {
    }

    public static function fromDomain(LeagueEntry $entry, int $rank, ?Summoner $summoner = null): self
    {
        $total = $entry->wins() + $entry->losses();
        $winRate = $total > 0 ? (int) round($entry->wins() / $total * 100) : 0;

        return new self(
            rank: $rank,
            puuid: $entry->puuid(),
            gameName: $summoner?->riotId()->gameName(),
            tagLine: $summoner?->riotId()->tagLine(),
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
