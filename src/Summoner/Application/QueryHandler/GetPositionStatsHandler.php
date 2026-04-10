<?php

declare(strict_types=1);

namespace App\Summoner\Application\QueryHandler;

use App\Summoner\Application\Dto\PositionStatsDto;
use App\Summoner\Application\Port\SummonerMatchStatsProviderInterface;
use App\Summoner\Application\Query\GetPositionStatsQuery;
use App\Summoner\Application\StatsCalculator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final readonly class GetPositionStatsHandler
{
    public function __construct(
        private SummonerMatchStatsProviderInterface $statsProvider,
    ) {
    }

    /**
     * @return PositionStatsDto[]
     */
    public function __invoke(GetPositionStatsQuery $query): array
    {
        $rows = $this->statsProvider->getStatsByPositionByPuuid($query->puuid->value());

        return array_map(static fn (array $row): PositionStatsDto => new PositionStatsDto(
            position: $row['position'],
            totalGames: $row['totalGames'],
            wins: $row['wins'],
            winRate: StatsCalculator::winRate($row['wins'], $row['totalGames']),
            avgKills: $row['avgKills'],
            avgDeaths: $row['avgDeaths'],
            avgAssists: $row['avgAssists'],
            avgKda: StatsCalculator::avgKda($row['avgKills'], $row['avgDeaths'], $row['avgAssists']),
            avgCs: $row['avgCs'],
        ), $rows);
    }
}
