<?php

declare(strict_types=1);

namespace App\Champion\Application\UseCase;

use App\Champion\Application\Command\SyncChampionsCommand;
use App\SharedContext\Application\Bus\CommandBusInterface;

final class SyncChampionsUseCase
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function execute(string $version, string $locale = 'fr_FR'): void
    {
        $this->commandBus->dispatch(new SyncChampionsCommand($version, $locale));
    }
}
