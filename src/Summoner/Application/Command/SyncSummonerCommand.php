<?php

declare(strict_types=1);

namespace App\Summoner\Application\Command;

use App\SharedContext\Domain\ValueObjet\Platform;

/**
 * Synchronous summoner import: never routed to an async transport.
 * Always forces a refresh regardless of last update time.
 */
final readonly class SyncSummonerCommand
{
    public function __construct(
        public string $puuid,
        public Platform $platform,
    ) {
    }
}
