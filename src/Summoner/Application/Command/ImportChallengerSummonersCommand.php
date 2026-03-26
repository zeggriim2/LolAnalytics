<?php

declare(strict_types=1);

namespace App\Summoner\Application\Command;

use App\SharedContext\Domain\ValueObjet\Platform;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final readonly class ImportChallengerSummonersCommand
{
    public function __construct(
        public Platform $platform,
        public Queue $queue = Queue::RANKED_SOLO,
    ) {
    }
}
