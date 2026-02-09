<?php

declare(strict_types=1);

namespace App\Match\Application\Command;

use App\SharedContext\Domain\ValueObjet\Region;

final class IngestMatchesByPuuidCommand
{
    public function __construct(
        public readonly string $puuid,
        public readonly Region $region,
        public readonly int $count = 20,
        public readonly int $start = 0,
        public readonly ?\DateTimeInterface $startTime = null,
        public readonly ?\DateTimeInterface $endTime = null,
        public readonly ?int $queue = null,
        public readonly ?string $type = null,
    ) {
    }
}
