<?php

declare(strict_types=1);

namespace App\Summoner\Application\QueryHandler;

use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Query\GetSummonerByPuuidQuery;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final readonly class GetSummonerByPuuidHandler
{
    public function __construct(
        private SummonerRepositoryInterface $summonerRepository,
    ) {
    }

    public function __invoke(GetSummonerByPuuidQuery $query): ?SummonerDto
    {
        $puuid = Puuid::fromString($query->puuid);
        $summoner = $this->summonerRepository->findByPuuid($puuid);

        if (null === $summoner) {
            return null;
        }

        return SummonerDto::fromDomain($summoner);
    }
}
