<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\CommandHandler;

use App\Match\Application\Command\IngestMatchCommand;
use App\Match\Application\Command\IngestMatchesByPuuidCommand;
use App\Match\Application\CommandHandler\IngestMatchesByPuuidHandler;
use App\Match\Infrastructure\Client\Enum\Type;
use App\Match\Infrastructure\Client\RiotApiClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class IngestMatchesByPuuidHandlerTest extends TestCase
{
    private RiotApiClientInterface $riotClient;
    private MessageBusInterface $commandBus;
    private IngestMatchesByPuuidHandler $handler;

    protected function setUp(): void
    {
        $this->riotClient = $this->createMock(RiotApiClientInterface::class);
        $this->commandBus = $this->createMock(MessageBusInterface::class);

        $this->handler = new IngestMatchesByPuuidHandler(
            $this->riotClient,
            $this->commandBus
        );
    }

    public function testIngestMatchesByPuuidDispatchesCommands(): void
    {
        $puuid = 'test-puuid-123';
        $region = 'europe';
        $command = new IngestMatchesByPuuidCommand($puuid, $region);

        $matchIds = [
            'EUW1_1234567890',
            'EUW1_1234567891',
            'EUW1_1234567892',
        ];

        $this->riotClient
            ->expects($this->once())
            ->method('fetchMatchesByPuuid')
            ->with($puuid, $region, Type::RANKED)
            ->willReturn($matchIds);

        // Should dispatch 3 IngestMatchCommand
        $this->commandBus
            ->expects($this->exactly(3))
            ->method('dispatch')
            ->with($this->callback(function ($dispatchedCommand) use ($matchIds, $region) {
                return $dispatchedCommand instanceof IngestMatchCommand
                    && in_array($dispatchedCommand->matchId, $matchIds, true)
                    && $dispatchedCommand->region === $region;
            }))
            ->willReturnCallback(fn ($command) => new Envelope($command));

        ($this->handler)($command);
    }

    public function testHandlesEmptyMatchList(): void
    {
        $puuid = 'test-puuid-456';
        $region = 'americas';
        $command = new IngestMatchesByPuuidCommand($puuid, $region);

        $this->riotClient
            ->expects($this->once())
            ->method('fetchMatchesByPuuid')
            ->with($puuid, $region, Type::RANKED)
            ->willReturn([]);

        // Should not dispatch any commands
        $this->commandBus
            ->expects($this->never())
            ->method('dispatch');

        ($this->handler)($command);
    }

    public function testIngestSingleMatch(): void
    {
        $puuid = 'test-puuid-789';
        $region = 'asia';
        $command = new IngestMatchesByPuuidCommand($puuid, $region);

        $matchIds = ['KR_9876543210'];

        $this->riotClient
            ->method('fetchMatchesByPuuid')
            ->willReturn($matchIds);

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (IngestMatchCommand $cmd) {
                return 'KR_9876543210' === $cmd->matchId && 'asia' === $cmd->region;
            }))
            ->willReturnCallback(fn ($command) => new Envelope($command));

        ($this->handler)($command);
    }
}
