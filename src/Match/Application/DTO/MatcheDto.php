<?php

declare(strict_types=1);

namespace App\Match\Application\DTO;

final class MatcheDto
{
    public function __construct(
        public readonly string $matchId,
        public readonly string $region
    ) {
    }
}
