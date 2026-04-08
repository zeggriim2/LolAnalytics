<?php

declare(strict_types=1);

namespace App\Tests\Unit\Summoner\Application\CommandHandler;

use App\League\Application\Query\GetLeaguePuuidsQuery;
use App\League\Domain\Enum\LeagueTier;
use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Application\Bus\QueryBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportSummonerCommand;
use App\Summoner\Application\Command\ImportTopLeagueSummonersCommand;
use App\Summoner\Application\CommandHandler\ImportTopLeagueSummonersHandler;
use PHPUnit\Framework\TestCase;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final class ImportTopLeagueSummonersHandlerTest extends TestCase
{
    public function testDispatchesOneCommandPerPuuid(): void
    {
        // Given
        $platform = Platform::EUW1;
        $queue = Queue::RANKED_SOLO;
        $tier = LeagueTier::CHALLENGER;
        $puuids = ['puuid-1', 'puuid-2', 'puuid-3'];

        $queryBus = $this->createMock(QueryBusInterface::class);
        $queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(fn (GetLeaguePuuidsQuery $q): bool => $q->platform === $platform && $q->tier === $tier && $q->queue === $queue))
            ->willReturn($puuids);

        $dispatchedCommands = [];
        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus
            ->expects($this->exactly(3))
            ->method('dispatch')
            ->willReturnCallback(function (object $command) use (&$dispatchedCommands) {
                $dispatchedCommands[] = $command;

                return null;
            });

        // When
        (new ImportTopLeagueSummonersHandler($queryBus, $commandBus))(
            new ImportTopLeagueSummonersCommand($platform, $tier, $queue),
        );

        // Then
        $this->assertCount(3, $dispatchedCommands);

        foreach ($dispatchedCommands as $i => $command) {
            $this->assertInstanceOf(ImportSummonerCommand::class, $command);
            $this->assertSame($puuids[$i], $command->puuid);
            $this->assertSame($platform, $command->platform);
        }
    }

    public function testDispatchesNothingWhenLeagueIsEmpty(): void
    {
        // Given
        $queryBus = $this->createStub(QueryBusInterface::class);
        $queryBus->method('handle')->willReturn([]);

        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus->expects($this->never())->method('dispatch');

        // When
        (new ImportTopLeagueSummonersHandler($queryBus, $commandBus))(
            new ImportTopLeagueSummonersCommand(Platform::EUW1),
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('tierProvider')]
    public function testForwardsTierToQueryBus(LeagueTier $tier): void
    {
        // Given
        $platform = Platform::NA1;
        $queue = Queue::RANKED_FLEX_SR;

        $queryBus = $this->createMock(QueryBusInterface::class);
        $queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(fn (GetLeaguePuuidsQuery $q): bool => $q->tier === $tier && $q->queue === $queue && $q->platform === $platform))
            ->willReturn([]);

        $commandBus = $this->createStub(CommandBusInterface::class);

        // When
        (new ImportTopLeagueSummonersHandler($queryBus, $commandBus))(
            new ImportTopLeagueSummonersCommand($platform, $tier, $queue),
        );
    }

    /**
     * @return iterable<string, array{LeagueTier}>
     */
    public static function tierProvider(): iterable
    {
        yield 'challenger' => [LeagueTier::CHALLENGER];
        yield 'grandmaster' => [LeagueTier::GRANDMASTER];
        yield 'master' => [LeagueTier::MASTER];
    }
}
