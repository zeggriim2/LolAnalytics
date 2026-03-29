<?php

declare(strict_types=1);

namespace App\Tests\Unit\Summoner\Application\CommandHandler;

use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportSummonerCommand;
use App\Summoner\Application\Command\ImportTopLeagueSummonersCommand;
use App\Summoner\Application\CommandHandler\ImportTopLeagueSummonersHandler;
use App\Summoner\Application\Port\RiotLeagueProviderInterface;
use App\Summoner\Domain\Enum\TopLeagueTier;
use PHPUnit\Framework\TestCase;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final class ImportTopLeagueSummonersHandlerTest extends TestCase
{
    public function testDispatchesOneCommandPerPuuid(): void
    {
        // Given
        $platform = Platform::EUW1;
        $queue = Queue::RANKED_SOLO;
        $tier = TopLeagueTier::CHALLENGER;
        $puuids = ['puuid-1', 'puuid-2', 'puuid-3'];

        $leagueProvider = $this->createMock(RiotLeagueProviderInterface::class);
        $leagueProvider
            ->expects($this->once())
            ->method('getTopLeaguePuuids')
            ->with($platform, $queue, $tier)
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
        (new ImportTopLeagueSummonersHandler($leagueProvider, $commandBus))(
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

    public function testDispatchesNothingWhenNoPuuids(): void
    {
        // Given
        $leagueProvider = $this->createStub(RiotLeagueProviderInterface::class);
        $leagueProvider->method('getTopLeaguePuuids')->willReturn([]);

        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus->expects($this->never())->method('dispatch');

        // When
        (new ImportTopLeagueSummonersHandler($leagueProvider, $commandBus))(
            new ImportTopLeagueSummonersCommand(Platform::EUW1),
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('tierProvider')]
    public function testForwardsTierToLeagueProvider(TopLeagueTier $tier): void
    {
        // Given
        $platform = Platform::NA1;
        $queue = Queue::RANKED_FLEX_SR;

        $leagueProvider = $this->createMock(RiotLeagueProviderInterface::class);
        $leagueProvider
            ->expects($this->once())
            ->method('getTopLeaguePuuids')
            ->with($platform, $queue, $tier)
            ->willReturn([]);

        $commandBus = $this->createStub(CommandBusInterface::class);

        // When
        (new ImportTopLeagueSummonersHandler($leagueProvider, $commandBus))(
            new ImportTopLeagueSummonersCommand($platform, $tier, $queue),
        );
    }

    /**
     * @return iterable<string, array{TopLeagueTier}>
     */
    public static function tierProvider(): iterable
    {
        yield 'challenger' => [TopLeagueTier::CHALLENGER];
        yield 'grandmaster' => [TopLeagueTier::GRANDMASTER];
        yield 'master' => [TopLeagueTier::MASTER];
    }
}
