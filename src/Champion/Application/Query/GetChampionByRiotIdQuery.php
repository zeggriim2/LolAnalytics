<?php

declare(strict_types=1);

namespace App\Champion\Application\Query;

final readonly class GetChampionByRiotIdQuery
{
    public function __construct(
        public string $riotId,
        public ?string $version = null,
    ) {
    }
}
