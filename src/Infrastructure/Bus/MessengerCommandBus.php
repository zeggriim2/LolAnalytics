<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\SharedContext\Bus\CommandBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerCommandBus implements CommandBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {}

    public function dispatch(object $command): mixed
    {
        return $this->commandBus->dispatch($command);
    }
}
