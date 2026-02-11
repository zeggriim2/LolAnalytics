<?php

declare(strict_types=1);

namespace App\Summoner\Application\QueryHandler;

use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Query\ListSummonersQuery;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final readonly class ListSummonersHandler
{
    public function __construct(
        private SummonerRepositoryInterface $summonerRepository,
    ) {
    }

    /**
     * @return SummonerDto[]
     */
    public function __invoke(ListSummonersQuery $query): array
    {
        $summoners = $this->summonerRepository->findAll($query->limit, $query->offset);

        return array_map(
            fn ($summoner) => SummonerDto::fromDomain($summoner),
            $summoners
        );
    }
}
