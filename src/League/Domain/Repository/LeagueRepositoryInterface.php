<?php

declare(strict_types=1);

namespace App\League\Domain\Repository;

use App\League\Domain\Enum\LeagueTier;
use App\League\Domain\Model\League;
use App\SharedContext\Domain\ValueObjet\Platform;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

interface LeagueRepositoryInterface
{
    public function findByTierQueuePlatform(LeagueTier $tier, Queue $queue, Platform $platform): ?League;

    public function save(League $league): void;
}
