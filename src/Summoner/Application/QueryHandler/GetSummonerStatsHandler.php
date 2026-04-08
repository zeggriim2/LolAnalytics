<?php

declare(strict_types=1);

namespace App\Summoner\Application\QueryHandler;

use App\Summoner\Application\Dto\SummonerStatsDto;
use App\Summoner\Application\Port\SummonerMatchStatsProviderInterface;
use App\Summoner\Application\Query\GetSummonerStatsQuery;
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

        return SummonerStatsDto::fromArray($data);
    }
}
