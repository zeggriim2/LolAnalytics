<?php

declare(strict_types=1);

namespace App\Match\Application\Command;

use App\SharedContext\Domain\ValueObjet\Region;

final class IngestMatchCommand
{
    public function __construct(
        public readonly string $matchId,
        public readonly Region $region,
        public readonly ?\DateTimeImmutable $startDate = null,
        public readonly ?\DateTimeImmutable $endTime = null,
        public readonly ?string $queue = null,
        public readonly ?string $type = null,
    ) {
    }
}
