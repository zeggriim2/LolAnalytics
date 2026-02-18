<?php

declare(strict_types=1);

namespace App\Match\Application\QueryHandler;

use App\Match\Application\Query\ListMatchesQuery;
use App\Match\Application\ReadModel\MatchReadModel;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class ListMatchesHandler
{
    public function __construct(private MatchRepositoryInterface $repository)
    {
    }

    /**
     * @return PaginatedResult<MatchReadModel>
     */
    public function __invoke(ListMatchesQuery $query): PaginatedResult
    {
        $pagination = $query->pagination;

        $matches = $this->repository->findPaginated($pagination->offset(), $pagination->limit);
        $total = $this->repository->count();

        return new PaginatedResult(
            items: array_map(MatchReadModel::fromDomain(...), $matches),
            total: $total,
            page: $pagination->page,
            limit: $pagination->limit,
        );
    }
}
