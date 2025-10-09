<?php

declare(strict_types=1);

namespace App\Match\Application\UseCase;

use App\Match\Application\Command\IngestMatchesByPuuidCommand;
use App\SharedContext\Application\Bus\CommandBusInterface;

final class IngestMatchesUseCase
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    public function execute(string $puuid, string $region): void
    {
        $this->commandBus->dispatch(new IngestMatchesByPuuidCommand($puuid, $region));
    }
}
