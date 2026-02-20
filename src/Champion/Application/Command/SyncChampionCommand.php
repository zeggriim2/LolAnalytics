<?php

declare(strict_types=1);

namespace App\Champion\Application\Command;

final readonly class SyncChampionCommand
{
    public function __construct(
        public string $champion,
        public string $version,
        public string $locale = 'fr_FR',
    ) {
    }
}
