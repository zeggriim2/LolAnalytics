<?php

declare(strict_types=1);

namespace App\Match\Application\QueryHandler;

use App\Match\Application\Port\SummonerAdapterInterface;
use App\Match\Application\Query\GetMatchByIdQuery;
use App\Match\Application\ReadModel\MatchDetailReadModel;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class GetMatchByIdHandler
{
    public function __construct(
        private MatchRepositoryInterface $repository,
        private SummonerAdapterInterface $summonerProvider,
    ) {
    }

    public function __invoke(GetMatchByIdQuery $query): ?MatchDetailReadModel
    {
        $match = $this->repository->findById($query->id);

        if (null === $match) {
            return null;
        }

        $puuids = array_map(
            static fn ($p) => (string) $p->summonerPuuid(),
            $match->participants()
        );

        $gameNames = $this->summonerProvider->findGameNamesByPuuids($puuids);

        return MatchDetailReadModel::fromDomain($match, $gameNames);
    }
}
