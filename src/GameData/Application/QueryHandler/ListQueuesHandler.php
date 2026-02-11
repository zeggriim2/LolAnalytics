<?php

declare(strict_types=1);

namespace App\GameData\Application\QueryHandler;

use App\GameData\Application\Query\ListQueuesQuery;
use App\GameData\Application\ReadModel\QueueReadModel;
use App\GameData\Domain\Repository\QueueRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class ListQueuesHandler
{
    public function __construct(private readonly QueueRepositoryInterface $repository)
    {
    }

    /**
     * @return QueueReadModel[]
     */
    public function __invoke(ListQueuesQuery $query): array
    {
        return array_map(
            QueueReadModel::fromDomain(...),
            $this->repository->findAll()
        );
    }
}
