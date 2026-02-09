<?php

declare(strict_types=1);

namespace App\Summoner\Application\Command;

use App\SharedContext\Domain\ValueObjet\Platform;

final readonly class ImportSummonerByRiotIdCommand
{
    public function __construct(
        public string $gameName,
        public string $tagLine,
        public Platform $platform,
    ) {
    }
}
