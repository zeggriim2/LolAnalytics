<?php

declare(strict_types=1);

namespace App\Tests\Unit\Summoner\Application\CommandHandler;

use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportChallengerSummonersCommand;
use App\Summoner\Application\Command\ImportSummonerCommand;
use App\Summoner\Application\CommandHandler\ImportChallengerSummonersHandler;
use App\Summoner\Application\Port\RiotLeagueProviderInterface;
use PHPUnit\Framework\TestCase;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final class ImportChallengerSummonersHandlerTest extends TestCase
{
    public function testDispatchesOneCommandPerPuuid(): void
    {
        // Given
        $platform = Platform::EUW1;
        $queue = Queue::RANKED_SOLO;
        $puuids = ['puuid-1', 'puuid-2', 'puuid-3'];

        $leagueProvider = $this->createMock(RiotLeagueProviderInterface::class);
        $leagueProvider
            ->expects($this->once())
            ->method('getChallengerPuuids')
            ->with($platform, $queue)
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
        (new ImportChallengerSummonersHandler($leagueProvider, $commandBus))(
            new ImportChallengerSummonersCommand($platform, $queue),
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
        $leagueProvider->method('getChallengerPuuids')->willReturn([]);

        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus->expects($this->never())->method('dispatch');

        // When
        (new ImportChallengerSummonersHandler($leagueProvider, $commandBus))(
            new ImportChallengerSummonersCommand(Platform::EUW1),
        );
    }

    public function testForwardsQueueToLeagueProvider(): void
    {
        // Given
        $platform = Platform::NA1;
        $queue = Queue::RANKED_FLEX_SR;

        $leagueProvider = $this->createMock(RiotLeagueProviderInterface::class);
        $leagueProvider
            ->expects($this->once())
            ->method('getChallengerPuuids')
            ->with($platform, $queue)
            ->willReturn([]);

        $commandBus = $this->createStub(CommandBusInterface::class);

        // When
        (new ImportChallengerSummonersHandler($leagueProvider, $commandBus))(
            new ImportChallengerSummonersCommand($platform, $queue),
        );
    }
}
