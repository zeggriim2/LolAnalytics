<?php

declare(strict_types=1);

namespace App\Champion\Application\UseCase;

use App\Champion\Application\Command\SyncChampionCommand;
use App\SharedContext\Application\Bus\CommandBusInterface;

final class SyncChampionUseCase
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function execute(string $champion, string $version, string $locale = 'fr_FR'): void
    {
        $this->commandBus->dispatch(new SyncChampionCommand($champion, $version, $locale));
    }
}
