<?php

declare(strict_types=1);

namespace App\GameData\Application\QueryHandler;

use App\GameData\Application\Query\ListMapsQuery;
use App\GameData\Application\ReadModel\MapReadModel;
use App\GameData\Domain\Repository\MapRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class ListMapsHandler
{
    public function __construct(private readonly MapRepositoryInterface $repository)
    {
    }

    /**
     * @return MapReadModel[]
     */
    public function __invoke(ListMapsQuery $query): array
    {
        return array_map(
            MapReadModel::fromDomain(...),
            $this->repository->findAll()
        );
    }
}
