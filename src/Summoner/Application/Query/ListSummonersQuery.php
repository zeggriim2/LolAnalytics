<?php

declare(strict_types=1);

namespace App\Summoner\Application\Query;

use App\SharedContext\Domain\Pagination\PaginationRequest;

final readonly class ListSummonersQuery
{
    public function __construct(
        public readonly PaginationRequest $pagination = new PaginationRequest(),
    ) {
    }
}
