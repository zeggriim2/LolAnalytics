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

    /**
     * @return array<int, array{
     *     position: string,
     *     totalGames: int,
     *     wins: int,
     *     avgKills: float,
     *     avgDeaths: float,
     *     avgAssists: float,
     *     avgCs: float,
     * }>
     */
    public function getStatsByPositionByPuuid(string $puuid): array;
}
