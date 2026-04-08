<?php

declare(strict_types=1);

namespace App\Tests\Unit\League\Application\CommandHandler;

use App\League\Application\Command\RefreshLeagueCommand;
use App\League\Application\CommandHandler\RefreshLeagueHandler;
use App\League\Application\Dto\LeagueEntryDto;
use App\League\Application\Port\RiotLeagueProviderInterface;
use App\League\Domain\Enum\LeagueTier;
use App\League\Domain\Model\League;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use PHPUnit\Framework\TestCase;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final class RefreshLeagueHandlerTest extends TestCase
{
    public function testSavesLeagueWithEntriesFromProvider(): void
    {
        // Given
        $command = new RefreshLeagueCommand(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO);

        $dtos = [
            new LeagueEntryDto('puuid-1', 1500, 100, 50, null, true, false, false),
            new LeagueEntryDto('puuid-2', 800, 60, 40, null, false, true, false),
        ];

        $leagueProvider = $this->createMock(RiotLeagueProviderInterface::class);
        $leagueProvider
            ->expects($this->once())
            ->method('getLeagueEntries')
            ->with(Platform::EUW1, Queue::RANKED_SOLO, LeagueTier::CHALLENGER)
            ->willReturn($dtos);

        $leagueRepository = $this->createMock(LeagueRepositoryInterface::class);
        $leagueRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (League $league) {
                return LeagueTier::CHALLENGER === $league->tier()
                    && Queue::RANKED_SOLO === $league->queue()
                    && Platform::EUW1 === $league->platform()
                    && 2 === count($league->entries())
                    && 2300 === $league->totalLp();
            }));

        // When
        (new RefreshLeagueHandler($leagueProvider, $leagueRepository))($command);
    }

    public function testSavesEmptyLeagueWhenProviderReturnsNothing(): void
    {
        // Given
        $command = new RefreshLeagueCommand(Platform::EUW1, LeagueTier::MASTER, Queue::RANKED_SOLO);

        $leagueProvider = $this->createStub(RiotLeagueProviderInterface::class);
        $leagueProvider->method('getLeagueEntries')->willReturn([]);

        $leagueRepository = $this->createStub(LeagueRepositoryInterface::class);

        // No exception = success
        $this->expectNotToPerformAssertions();

        // When
        (new RefreshLeagueHandler($leagueProvider, $leagueRepository))($command);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('tierProvider')]
    public function testForwardsTierToProvider(LeagueTier $tier): void
    {
        // Given
        $command = new RefreshLeagueCommand(Platform::EUW1, $tier, Queue::RANKED_SOLO);

        $leagueProvider = $this->createMock(RiotLeagueProviderInterface::class);
        $leagueProvider
            ->expects($this->once())
            ->method('getLeagueEntries')
            ->with(Platform::EUW1, Queue::RANKED_SOLO, $tier)
            ->willReturn([]);

        $leagueRepository = $this->createStub(LeagueRepositoryInterface::class);

        // When
        (new RefreshLeagueHandler($leagueProvider, $leagueRepository))($command);
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
