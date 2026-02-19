<?php

declare(strict_types=1);

namespace App\Match\Application\QueryHandler;

use App\Match\Application\Query\ListMatchesBySummonerQuery;
use App\Match\Application\ReadModel\MatchDetailReadModel;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class ListMatchesBySummonerHandler
{
    public function __construct(private MatchRepositoryInterface $repository)
    {
    }

    /**
     * @return PaginatedResult<MatchDetailReadModel>
     */
    public function __invoke(ListMatchesBySummonerQuery $query): PaginatedResult
    {
        $pagination = $query->pagination;

        $matches = $this->repository->findBySummonerPuuid($query->puuid, $pagination->offset(), $pagination->limit);
        $total = $this->repository->countBySummonerPuuid($query->puuid);

        return new PaginatedResult(
            items: array_map(MatchDetailReadModel::fromDomain(...), $matches),
            total: $total,
            page: $pagination->page,
            limit: $pagination->limit,
        );
    }
}
