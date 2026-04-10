<?php

declare(strict_types=1);

namespace App\Summoner\Application\Dto;

final readonly class SummonerStatsDto
{
    public function __construct(
        public int $totalGames,
        public int $wins,
        public int $losses,
        public float $winRate,
        public float $avgKills,
        public float $avgDeaths,
        public float $avgAssists,
        public float $avgKda,
        public float $avgCs,
        public float $avgCsPerMin,
        public int $avgGold,
        public ?int $favoriteChampionId,
    ) {
    }
}
