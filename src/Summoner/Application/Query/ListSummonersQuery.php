<?php

declare(strict_types=1);

namespace App\Summoner\Application\Query;

final readonly class ListSummonersQuery
{
    public function __construct(
        public ?int $limit = null,
        public ?int $offset = null,
    ) {
    }
}
