<?php

declare(strict_types=1);

namespace App\GameData\Application\CommandHandler;

use App\GameData\Application\Command\SyncAllGameDataCommand;
use App\GameData\Application\Command\SyncGameModeCommand;
use App\GameData\Application\Command\SyncGameTypeCommand;
use App\GameData\Application\Command\SyncMapCommand;
use App\GameData\Application\Command\SyncQueueCommand;
use App\GameData\Application\Command\SyncSeasonCommand;
use App\GameData\Application\Command\SyncVersionCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler('command.bus')]
final class SyncAllGameDataHandler
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(SyncAllGameDataCommand $command): void
    {
        $this->commandBus->dispatch(new SyncGameModeCommand());
        $this->commandBus->dispatch(new SyncGameTypeCommand());
        $this->commandBus->dispatch(new SyncMapCommand());
        $this->commandBus->dispatch(new SyncSeasonCommand());
        $this->commandBus->dispatch(new SyncQueueCommand());
        $this->commandBus->dispatch(new SyncVersionCommand());
    }
}
