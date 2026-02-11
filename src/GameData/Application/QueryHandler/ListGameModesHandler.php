<?php

declare(strict_types=1);

namespace App\GameData\Application\QueryHandler;

use App\GameData\Application\Query\ListGameModesQuery;
use App\GameData\Application\ReadModel\GameModeReadModel;
use App\GameData\Domain\Repository\GameModeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class ListGameModesHandler
{
    public function __construct(private readonly GameModeRepositoryInterface $repository)
    {
    }

    /**
     * @return GameModeReadModel[]
     */
    public function __invoke(ListGameModesQuery $query): array
    {
        return array_map(
            GameModeReadModel::fromDomain(...),
            $this->repository->findAll()
        );
    }
}
