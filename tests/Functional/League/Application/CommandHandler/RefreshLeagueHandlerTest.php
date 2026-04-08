<?php

declare(strict_types=1);

namespace App\Tests\Functional\League\Application\CommandHandler;

use App\League\Application\Command\RefreshLeagueCommand;
use App\League\Application\Dto\LeagueEntryDto;
use App\League\Application\Port\RiotLeagueProviderInterface;
use App\League\Domain\Enum\LeagueTier;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Zeggriim\RiotApiDataDragon\Enum\Queue;
use Zenstruck\Foundry\Test\ResetDatabase;

final class RefreshLeagueHandlerTest extends KernelTestCase
{
    use ResetDatabase;

    private MessageBusInterface $commandBus;
    private LeagueRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $this->commandBus = $container->get('command.bus');
        $this->repository = $container->get(LeagueRepositoryInterface::class);
    }

    public function testRefreshPersistsLeagueEntries(): void
    {
        // Given
        $dtos = [
            new LeagueEntryDto('puuid-1', 1500, 100, 50, null, true, false, false),
            new LeagueEntryDto('puuid-2', 800, 60, 40, null, false, true, false),
        ];

        $mockProvider = $this->createStub(RiotLeagueProviderInterface::class);
        $mockProvider->method('getLeagueEntries')->willReturn($dtos);
        static::getContainer()->set(RiotLeagueProviderInterface::class, $mockProvider);

        // When
        $this->commandBus->dispatch(new RefreshLeagueCommand(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO));

        // Then
        $league = $this->repository->findByTierQueuePlatform(LeagueTier::CHALLENGER, Queue::RANKED_SOLO, Platform::EUW1);

        $this->assertNotNull($league);
        $this->assertCount(2, $league->entries());
        $this->assertSame(['puuid-1', 'puuid-2'], array_map(fn ($e) => $e->puuid(), $league->entries()));
    }

    public function testRefreshReplacesExistingEntries(): void
    {
        // Given: first refresh with 3 entries
        $firstProvider = $this->createStub(RiotLeagueProviderInterface::class);
        $firstProvider->method('getLeagueEntries')->willReturn([
            new LeagueEntryDto('old-1', 1000, 50, 30, null, false, false, false),
            new LeagueEntryDto('old-2', 900, 40, 20, null, false, false, false),
            new LeagueEntryDto('old-3', 800, 30, 10, null, false, false, false),
        ]);
        static::getContainer()->set(RiotLeagueProviderInterface::class, $firstProvider);
        $this->commandBus->dispatch(new RefreshLeagueCommand(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO));

        // Reboot kernel to allow replacing the service a second time
        self::ensureKernelShutdown();
        self::bootKernel();
        $container = static::getContainer();
        $this->commandBus = $container->get('command.bus');
        $this->repository = $container->get(LeagueRepositoryInterface::class);

        // When: second refresh with 1 entry
        $secondProvider = $this->createStub(RiotLeagueProviderInterface::class);
        $secondProvider->method('getLeagueEntries')->willReturn([
            new LeagueEntryDto('new-1', 2000, 200, 50, null, false, false, false),
        ]);
        static::getContainer()->set(RiotLeagueProviderInterface::class, $secondProvider);
        $this->commandBus->dispatch(new RefreshLeagueCommand(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO));

        // Then: only new entry remains
        $league = $this->repository->findByTierQueuePlatform(LeagueTier::CHALLENGER, Queue::RANKED_SOLO, Platform::EUW1);
        $this->assertNotNull($league);
        $this->assertCount(1, $league->entries());
        $this->assertSame('new-1', $league->entries()[0]->puuid());
    }
}
