<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\UseCase;

use App\Match\Application\Command\IngestMatchCommand;
use App\Match\Application\UseCase\IngestMatchUseCase;
use App\SharedContext\Application\Bus\CommandBusInterface;
use PHPUnit\Framework\TestCase;

final class IngestMatchUseCaseTest extends TestCase
{
    private CommandBusInterface $commandBus;
    private IngestMatchUseCase $useCase;

    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->useCase = new IngestMatchUseCase($this->commandBus);
    }

    public function testExecuteDispatchesIngestMatchCommand(): void
    {
        $matchId = 'EUW1_1234567890';
        $region = 'europe';

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function ($command) use ($matchId, $region) {
                return $command instanceof IngestMatchCommand
                    && $command->matchId === $matchId
                    && $command->region === $region;
            }));

        $this->useCase->execute($matchId, $region);
    }

    public function testExecuteWithDifferentRegions(): void
    {
        $matchId = 'NA1_9876543210';
        $region = 'americas';

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function ($command) use ($matchId, $region) {
                return $command instanceof IngestMatchCommand
                    && $command->matchId === $matchId
                    && $command->region === $region;
            }));

        $this->useCase->execute($matchId, $region);
    }

    public function testExecuteCreatesCorrectCommandObject(): void
    {
        $matchId = 'KR_1112223334';
        $region = 'asia';

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(IngestMatchCommand::class));

        $this->useCase->execute($matchId, $region);
    }
}
