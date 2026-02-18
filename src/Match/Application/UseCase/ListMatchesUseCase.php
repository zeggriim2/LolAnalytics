<?php

declare(strict_types=1);

namespace App\Match\Application\UseCase;

use App\Match\Application\Query\ListMatchesQuery;
use App\SharedContext\Application\Bus\QueryBusInterface;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\SharedContext\Domain\Pagination\PaginationRequest;

final class ListMatchesUseCase
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    /**
     * @return PaginatedResult<\App\Match\Application\ReadModel\MatchReadModel>
     */
    public function execute(int $page = 1, int $limit = 20): PaginatedResult
    {
        return $this->queryBus->handle(
            new ListMatchesQuery(new PaginationRequest($page, $limit))
        );
    }
}
