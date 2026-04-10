<?php

declare(strict_types=1);

namespace App\Summoner\Application\Dto;

final readonly class PositionStatsDto
{
    public function __construct(
        public string $position,
        public int $totalGames,
        public int $wins,
        public float $winRate,
        public float $avgKills,
        public float $avgDeaths,
        public float $avgAssists,
        public float $avgKda,
        public float $avgCs,
    ) {
    }
}
