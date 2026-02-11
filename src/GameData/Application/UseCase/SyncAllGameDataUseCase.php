<?php

declare(strict_types=1);

namespace App\GameData\Application\UseCase;

use App\GameData\Application\Command\SyncAllGameDataCommand;
use App\SharedContext\Application\Bus\CommandBusInterface;

final class SyncAllGameDataUseCase
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function execute(): void
    {
        $this->commandBus->dispatch(new SyncAllGameDataCommand());
    }
}
