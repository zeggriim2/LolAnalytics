<?php

declare(strict_types=1);

namespace App\Application\Version\UseCase;

use App\Application\Version\Command\FetchVersionsCommand;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

final class FetchVersionsUseCase
{
    public function __construct(
        private readonly MessageBusInterface $commandBus
    ) {
    }

    public function execute(bool $forceUpdate = false): string
    {
        $requestId = Uuid::v4()->toRfc4122();

        $message = new FetchVersionsCommand($forceUpdate, $requestId);
        $this->commandBus->dispatch($message);

        return $requestId;
    }
}
