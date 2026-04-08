<?php

declare(strict_types=1);

namespace App\Summoner\Application\Port;

interface SummonerMatchStatsProviderInterface
{
    /**
     * @return array{
     *     totalGames: int,
     *     wins: int,
     *     losses: int,
     *     avgKills: float,
     *     avgDeaths: float,
     *     avgAssists: float,
     *     avgCs: float,
     *     avgCsPerMin: float,
     *     avgGold: int,
     *     avgDurationSeconds: int,
     *     favoriteChampionId: int|null,
     * }
     */
    public function getAggregateStatsByPuuid(string $puuid): array;
}
