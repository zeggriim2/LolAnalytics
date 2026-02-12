<?php

declare(strict_types=1);

namespace App\GameData\Application\UseCase;

use App\GameData\Application\Command\SyncVersionCommand;
use App\SharedContext\Application\Bus\CommandBusInterface;

final class SyncVersionUseCase
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function execute(): void
    {
        $this->commandBus->dispatch(new SyncVersionCommand());
    }
}
