<?php

declare(strict_types=1);

namespace App\Summoner\Application\Port;

use App\SharedContext\Domain\ValueObjet\Platform;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

interface RiotLeagueProviderInterface
{
    /**
     * @return string[] List of PUUIDs from the challenger league
     */
    public function getChallengerPuuids(Platform $platform, Queue $queue): array;
}
