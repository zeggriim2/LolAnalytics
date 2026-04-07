<?php

declare(strict_types=1);

namespace App\League\Application\Command;

use App\League\Domain\Enum\LeagueTier;
use App\SharedContext\Domain\ValueObjet\Platform;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final readonly class RefreshLeagueCommand
{
    public function __construct(
        public Platform $platform,
        public LeagueTier $tier,
        public Queue $queue,
    ) {
    }
}
