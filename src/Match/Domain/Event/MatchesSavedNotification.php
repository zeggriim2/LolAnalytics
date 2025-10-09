<?php

declare(strict_types=1);

namespace App\Match\Domain\Event;

final class MatchesSavedNotification
{
    /**
     * @param string[] $matchIds
     */
    public function __construct(
        public readonly array $matchIds,
        public readonly string $region,
        public readonly \DateTimeImmutable $occurredAt
    ) {
    }
}
