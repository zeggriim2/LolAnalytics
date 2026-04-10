<?php

declare(strict_types=1);

namespace App\Summoner\Application\QueryHandler;

use App\Summoner\Application\Dto\PositionStatsDto;
use App\Summoner\Application\Port\SummonerMatchStatsProviderInterface;
use App\Summoner\Application\Query\GetPositionStatsQuery;
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

        return array_map(function (array $row): PositionStatsDto {
            $totalGames = $row['totalGames'];
            $wins = $row['wins'];
            $avgKills = $row['avgKills'];
            $avgDeaths = $row['avgDeaths'];
            $avgAssists = $row['avgAssists'];

            $winRate = $totalGames > 0
                ? round($wins / $totalGames * 100, 1)
                : 0.0;

            $avgKda = $avgDeaths > 0
                ? round(($avgKills + $avgAssists) / $avgDeaths, 2)
                : round($avgKills + $avgAssists, 2);

            return new PositionStatsDto(
                position: $row['position'],
                totalGames: $totalGames,
                wins: $wins,
                winRate: $winRate,
                avgKills: $avgKills,
                avgDeaths: $avgDeaths,
                avgAssists: $avgAssists,
                avgKda: $avgKda,
                avgCs: $row['avgCs'],
            );
        }, $rows);
    }
}
