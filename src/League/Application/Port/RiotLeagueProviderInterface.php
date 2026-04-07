<?php

declare(strict_types=1);

namespace App\League\Application\Port;

use App\League\Application\Dto\LeagueEntryDto;
use App\League\Domain\Enum\LeagueTier;
use App\SharedContext\Domain\ValueObjet\Platform;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

interface RiotLeagueProviderInterface
{
    /**
     * @return LeagueEntryDto[]
     */
    public function getLeagueEntries(Platform $platform, Queue $queue, LeagueTier $tier): array;
}
