<?php

declare(strict_types=1);

namespace App\League\Application\Dto;

final readonly class LeagueEntryDto
{
    public function __construct(
        public string $puuid,
        public string $summonerId,
        public int $leaguePoints,
        public int $wins,
        public int $losses,
        public ?string $rank,
        public bool $hotStreak,
        public bool $veteran,
        public bool $freshBlood,
    ) {
    }
}
