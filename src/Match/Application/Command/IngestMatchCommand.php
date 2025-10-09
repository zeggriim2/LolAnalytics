<?php

declare(strict_types=1);

namespace App\Match\Application\Command;

final class IngestMatchCommand
{
    public function __construct(public readonly string $matchId, public readonly string $region)
    {
    }
}
