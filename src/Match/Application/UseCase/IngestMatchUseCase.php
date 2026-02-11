<?php

declare(strict_types=1);

namespace App\Match\Application\UseCase;

use App\Match\Application\Command\IngestMatchCommand;
use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Domain\ValueObjet\Region;

final class IngestMatchUseCase
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    public function execute(
        string $matchId,
        Region $region,
        ?\DateTimeImmutable $startTime = null,
        ?\DateTimeImmutable $endTime = null,
        ?string $queue = null,
        ?string $type = null,
    ): void {
        $this->commandBus->dispatch(new IngestMatchCommand($matchId, $region));
    }
}
