<?php

declare(strict_types=1);

namespace App\GameData\Application\QueryHandler;

use App\GameData\Application\Query\ListGameTypesQuery;
use App\GameData\Application\ReadModel\GameTypeReadModel;
use App\GameData\Domain\Repository\GameTypeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class ListGameTypesHandler
{
    public function __construct(private readonly GameTypeRepositoryInterface $repository)
    {
    }

    /**
     * @return GameTypeReadModel[]
     */
    public function __invoke(ListGameTypesQuery $query): array
    {
        return array_map(
            GameTypeReadModel::fromDomain(...),
            $this->repository->findAll()
        );
    }
}
