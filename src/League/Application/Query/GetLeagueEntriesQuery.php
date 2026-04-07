<?php

declare(strict_types=1);

namespace App\League\Application\Query;

use App\League\Domain\Enum\LeagueTier;
use App\SharedContext\Domain\ValueObjet\Platform;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final readonly class GetLeagueEntriesQuery
{
    public function __construct(
        public Platform $platform,
        public LeagueTier $tier,
        public Queue $queue,
        public int $page = 1,
        public int $limit = 50,
    ) {
    }
}
