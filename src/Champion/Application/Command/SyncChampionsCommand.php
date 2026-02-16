<?php

declare(strict_types=1);

namespace App\Champion\Application\Command;

final readonly class SyncChampionsCommand
{
    public function __construct(
        public string $version,
        public string $locale = 'fr_FR',
    ) {
    }
}
