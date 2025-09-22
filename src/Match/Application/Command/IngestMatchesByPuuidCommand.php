<?php

declare(strict_types=1);

namespace App\Match\Application\Command;

final class IngestMatchesByPuuidCommand
{
    public function __construct(
        public readonly string $puuid,
        public readonly string $region,
        public readonly int $count = 20,
    ) {}
}
