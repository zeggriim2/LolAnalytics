<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\CommandHandler;

use App\Match\Application\Command\IngestMatchCommand;
use App\Match\Application\Command\IngestMatchesByPuuidCommand;
use App\Match\Application\CommandHandler\IngestMatchesByPuuidHandler;
use App\SharedContext\Domain\ValueObjet\Region;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Zeggriim\RiotApiDataDragon\DataLeague\Endpoint\MatchApiInterface;
use Zeggriim\RiotApiDataDragon\DataLeague\Filter\MatchFilter;
use Zeggriim\RiotApiDataDragon\Enum\Region as RiotRegion;

final class IngestMatchesByPuuidHandlerTest extends TestCase
{
    private MatchApiInterface $matchApi;
    private MessageBusInterface $commandBus;
    private IngestMatchesByPuuidHandler $handler;

    protected function setUp(): void
    {
        $this->matchApi = $this->createMock(MatchApiInterface::class);
        $this->commandBus = $this->createMock(MessageBusInterface::class);

        $this->handler = new IngestMatchesByPuuidHandler(
            $this->matchApi,
            $this->commandBus
        );
    }

    public function testIngestMatchesByPuuidDispatchesCommands(): void
    {
        $puuid = 'test-puuid-123';
        $region = Region::EUROPE;
        $command = new IngestMatchesByPuuidCommand($puuid, $region);

        $matchIds = [
            'EUW1_1234567890',
            'EUW1_1234567891',
            'EUW1_1234567892',
        ];

        $matchFilter = new MatchFilter();

        $this->matchApi
            ->expects($this->once())
            ->method('getMatches')
            ->with($puuid, RiotRegion::EUROPE, $matchFilter)
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
        $region = Region::AMERICAS;
        $command = new IngestMatchesByPuuidCommand($puuid, $region);

        $matchFilter = new MatchFilter();

        $this->matchApi
            ->expects($this->once())
            ->method('getMatches')
            ->with($puuid, RiotRegion::AMERICAS, $matchFilter)
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
        $region = Region::ASIA;
        $command = new IngestMatchesByPuuidCommand($puuid, $region);

        $matchIds = ['KR_9876543210'];

        $this->matchApi
            ->method('getMatches')
            ->willReturn($matchIds);

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (IngestMatchCommand $cmd) {
                return 'KR_9876543210' === $cmd->matchId && 'asia' === $cmd->region->value;
            }))
            ->willReturnCallback(fn ($command) => new Envelope($command));

        ($this->handler)($command);
    }
}
