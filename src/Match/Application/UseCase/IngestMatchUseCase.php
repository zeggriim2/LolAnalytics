<?php

declare(strict_types=1);

namespace App\Match\Application\UseCase;

use App\Match\Application\Command\IngestMatchCommand;
use App\SharedContext\Application\Bus\CommandBusInterface;

final class IngestMatchUseCase
{
    public function __construct(private readonly CommandBusInterface $commandBus) {}
    public function execute(string $matchId, string $region): void
    {
        $this->commandBus->dispatch(new IngestMatchCommand($matchId, $region));
    }
}
