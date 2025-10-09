<?php

declare(strict_types=1);

namespace App\Match\Domain\Event;

use App\Match\Domain\ValueObjet\MatchId;

final class MatchesSavedNotification
{
    /**
     * @param MatchId[] $matchIds
     */
    public function __construct(
        public readonly array $matchIds,
        public readonly string $region,
        public readonly \DateTimeImmutable $occurredAt
    ) {
    }
}
