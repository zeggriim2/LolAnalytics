<?php

declare(strict_types=1);

namespace App\Match\Application\Query;

use App\Match\Application\Filter\MatchFilters;
use App\SharedContext\Domain\Pagination\PaginationRequest;

final class ListMatchesQuery
{
    public function __construct(
        public readonly PaginationRequest $pagination = new PaginationRequest(),
        public readonly MatchFilters $filters = new MatchFilters(),
    ) {
    }
}
