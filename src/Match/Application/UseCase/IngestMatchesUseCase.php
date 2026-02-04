<?php

declare(strict_types=1);

namespace App\Match\Application\UseCase;

use App\Match\Application\Command\IngestMatchesByPuuidCommand;
use App\Match\Domain\ValueObjet\Region;
use App\SharedContext\Application\Bus\CommandBusInterface;

final class IngestMatchesUseCase
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    public function execute(
        string $puuid,
        Region $region,
        int $count = 20,
        int $start = 0,
        ?\DateTimeInterface $startTime = null,
        ?\DateTimeInterface $endTime = null,
        ?int $queue = null,
        ?string $type = null,
    ): void {
        $this->commandBus->dispatch(new IngestMatchesByPuuidCommand(
            $puuid,
            $region,
            $count,
            $start,
            $startTime,
            $endTime,
            $queue,
            $type,
        ));
    }
}
