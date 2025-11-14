<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\UseCase;

use App\Match\Application\Command\IngestMatchesByPuuidCommand;
use App\Match\Application\UseCase\IngestMatchesUseCase;
use App\SharedContext\Application\Bus\CommandBusInterface;
use PHPUnit\Framework\TestCase;

final class IngestMatchesUseCaseTest extends TestCase
{
    private CommandBusInterface $commandBus;
    private IngestMatchesUseCase $useCase;

    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->useCase = new IngestMatchesUseCase($this->commandBus);
    }

    public function testExecuteDispatchesIngestMatchesByPuuidCommand(): void
    {
        $puuid = 'test-puuid-123';
        $region = 'europe';

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function ($command) use ($puuid, $region) {
                return $command instanceof IngestMatchesByPuuidCommand
                    && $command->puuid === $puuid
                    && $command->region === $region;
            }));

        $this->useCase->execute($puuid, $region);
    }

    public function testExecuteWithDifferentPuuidAndRegion(): void
    {
        $puuid = 'another-puuid-456';
        $region = 'americas';

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function ($command) use ($puuid, $region) {
                return $command instanceof IngestMatchesByPuuidCommand
                    && $command->puuid === $puuid
                    && $command->region === $region;
            }));

        $this->useCase->execute($puuid, $region);
    }

    public function testExecuteCreatesCorrectCommandObject(): void
    {
        $puuid = 'puuid-789';
        $region = 'asia';

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(IngestMatchesByPuuidCommand::class));

        $this->useCase->execute($puuid, $region);
    }
}
