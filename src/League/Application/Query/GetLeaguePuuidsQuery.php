<?php

declare(strict_types=1);

namespace App\League\Application\Query;

use App\League\Domain\Enum\LeagueTier;
use App\SharedContext\Domain\ValueObjet\Platform;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final readonly class GetLeaguePuuidsQuery
{
    public function __construct(
        public Platform $platform,
        public LeagueTier $tier,
        public Queue $queue,
    ) {
    }
}
