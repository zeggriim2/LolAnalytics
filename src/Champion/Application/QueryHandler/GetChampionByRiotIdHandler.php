<?php

declare(strict_types=1);

namespace App\Champion\Application\QueryHandler;

use App\Champion\Application\Query\GetChampionByRiotIdQuery;
use App\Champion\Application\ReadModel\ChampionDetailReadModel;
use App\Champion\Domain\Repository\ChampionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class GetChampionByRiotIdHandler
{
    public function __construct(
        private readonly ChampionRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetChampionByRiotIdQuery $query): ?ChampionDetailReadModel
    {
        $champion = null !== $query->version
            ? $this->repository->findByRiotIdAndVersion($query->riotId, $query->version)
            : $this->repository->findByRiotId($query->riotId);

        if (null === $champion) {
            return null;
        }

        return ChampionDetailReadModel::fromDomain($champion);
    }
}
