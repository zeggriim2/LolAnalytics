<?php

declare(strict_types=1);

namespace App\Summoner\Application\Command;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Domain\Enum\TopLeagueTier;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final readonly class ImportTopLeagueSummonersCommand
{
    public function __construct(
        public Platform $platform,
        public TopLeagueTier $tier = TopLeagueTier::CHALLENGER,
        public Queue $queue = Queue::RANKED_SOLO,
        public bool $force = false,
    ) {
    }
}
