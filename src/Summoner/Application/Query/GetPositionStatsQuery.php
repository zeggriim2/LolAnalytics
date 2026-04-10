<?php

declare(strict_types=1);

namespace App\Summoner\Application\Query;

use App\Summoner\Domain\ValueObject\Puuid;

final readonly class GetPositionStatsQuery
{
    public function __construct(public Puuid $puuid)
    {
    }
}
