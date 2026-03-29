<?php

declare(strict_types=1);

namespace App\Summoner\Application\Port;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Domain\Enum\TopLeagueTier;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

interface RiotLeagueProviderInterface
{
    /**
     * @return string[] List of PUUIDs from the given top-league tier
     */
    public function getTopLeaguePuuids(Platform $platform, Queue $queue, TopLeagueTier $tier): array;
}
