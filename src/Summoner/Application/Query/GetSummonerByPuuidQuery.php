<?php

declare(strict_types=1);

namespace App\Summoner\Application\Query;

final readonly class GetSummonerByPuuidQuery
{
    public function __construct(
        public string $puuid,
    ) {
    }
}
