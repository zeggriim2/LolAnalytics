<?php

declare(strict_types=1);

namespace App\Summoner\Application\Command;

use App\SharedContext\Domain\ValueObjet\Platform;

final readonly class ImportSummonerCommand
{
    public function __construct(
        public string $puuid,
        public Platform $platform,
    ) {
    }
}
