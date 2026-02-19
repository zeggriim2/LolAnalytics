<?php

declare(strict_types=1);

namespace App\Match\Application\Query;

use App\SharedContext\Domain\Pagination\PaginationRequest;

final class ListMatchesBySummonerQuery
{
    public function __construct(
        public readonly string $puuid,
        public readonly PaginationRequest $pagination = new PaginationRequest(),
    ) {
    }
}
