<?php

declare(strict_types=1);

namespace App\GameData\Application\QueryHandler;

use App\GameData\Application\Query\ListVersionsQuery;
use App\GameData\Application\ReadModel\VersionReadModel;
use App\GameData\Domain\Repository\VersionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class ListVersionsHandler
{
    public function __construct(private readonly VersionRepositoryInterface $repository)
    {
    }

    /**
     * @return VersionReadModel[]
     */
    public function __invoke(ListVersionsQuery $query): array
    {
        return array_map(
            VersionReadModel::fromDomain(...),
            $this->repository->findAll()
        );
    }
}
