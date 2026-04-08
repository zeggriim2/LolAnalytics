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

    /**
     * @param array{totalGames: int, wins: int, losses: int, avgKills: float, avgDeaths: float, avgAssists: float, avgCs: float, avgCsPerMin: float, avgGold: int, avgDurationSeconds: int, favoriteChampionId: int|null} $data
     */
    public static function fromArray(array $data): self
    {
        $winRate = $data['totalGames'] > 0
            ? round($data['wins'] / $data['totalGames'] * 100, 1)
            : 0.0;

        $avgKda = $data['avgDeaths'] > 0
            ? round(($data['avgKills'] + $data['avgAssists']) / $data['avgDeaths'], 2)
            : round($data['avgKills'] + $data['avgAssists'], 2);

        return new self(
            totalGames: $data['totalGames'],
            wins: $data['wins'],
            losses: $data['losses'],
            winRate: $winRate,
            avgKills: $data['avgKills'],
            avgDeaths: $data['avgDeaths'],
            avgAssists: $data['avgAssists'],
            avgKda: $avgKda,
            avgCs: $data['avgCs'],
            avgCsPerMin: $data['avgCsPerMin'],
            avgGold: $data['avgGold'],
            favoriteChampionId: $data['favoriteChampionId'],
        );
    }
}
