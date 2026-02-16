<?php

declare(strict_types=1);

namespace App\Champion\Application\QueryHandler;

use App\Champion\Application\Query\ListChampionsQuery;
use App\Champion\Application\ReadModel\ChampionReadModel;
use App\Champion\Domain\Repository\ChampionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class ListChampionsHandler
{
    public function __construct(
        private readonly ChampionRepositoryInterface $repository,
    ) {
    }

    /**
     * @return ChampionReadModel[]
     */
    public function __invoke(ListChampionsQuery $query): array
    {
        $champions = null !== $query->version
            ? $this->repository->findByVersion($query->version)
            : $this->repository->findAll();

        return array_map(
            ChampionReadModel::fromDomain(...),
            $champions,
        );
    }
}
