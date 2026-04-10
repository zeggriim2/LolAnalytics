<?php

declare(strict_types=1);

namespace App\Summoner\Application\QueryHandler;

use App\Summoner\Application\Dto\SummonerStatsDto;
use App\Summoner\Application\Port\SummonerMatchStatsProviderInterface;
use App\Summoner\Application\Query\GetSummonerStatsQuery;
use App\Summoner\Application\StatsCalculator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final readonly class GetSummonerStatsHandler
{
    public function __construct(
        private SummonerMatchStatsProviderInterface $statsProvider,
    ) {
    }

    public function __invoke(GetSummonerStatsQuery $query): SummonerStatsDto
    {
        $data = $this->statsProvider->getAggregateStatsByPuuid($query->puuid->value());

        return new SummonerStatsDto(
            totalGames: $data['totalGames'],
            wins: $data['wins'],
            losses: $data['losses'],
            winRate: StatsCalculator::winRate($data['wins'], $data['totalGames']),
            avgKills: $data['avgKills'],
            avgDeaths: $data['avgDeaths'],
            avgAssists: $data['avgAssists'],
            avgKda: StatsCalculator::avgKda($data['avgKills'], $data['avgDeaths'], $data['avgAssists']),
            avgCs: $data['avgCs'],
            avgCsPerMin: $data['avgCsPerMin'],
            avgGold: $data['avgGold'],
            favoriteChampionId: $data['favoriteChampionId'],
        );
    }
}
