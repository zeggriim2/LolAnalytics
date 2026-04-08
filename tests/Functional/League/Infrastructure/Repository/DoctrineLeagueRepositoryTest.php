<?php

declare(strict_types=1);

namespace App\Tests\Functional\League\Infrastructure\Repository;

use App\League\Domain\Enum\LeagueTier;
use App\League\Domain\Model\League;
use App\League\Domain\Model\LeagueEntry;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zeggriim\RiotApiDataDragon\Enum\Queue;
use Zenstruck\Foundry\Test\ResetDatabase;

final class DoctrineLeagueRepositoryTest extends KernelTestCase
{
    use ResetDatabase;

    private LeagueRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->repository = static::getContainer()->get(LeagueRepositoryInterface::class);
    }

    public function testSaveAndFindLeague(): void
    {
        // Given
        $league = $this->makeLeague(LeagueTier::CHALLENGER, Platform::EUW1, [
            ['puuid-1', 1500],
            ['puuid-2', 800],
        ]);

        // When
        $this->repository->save($league);
        $found = $this->repository->findByTierQueuePlatform(LeagueTier::CHALLENGER, Queue::RANKED_SOLO, Platform::EUW1);

        // Then
        $this->assertNotNull($found);
        $this->assertSame(LeagueTier::CHALLENGER, $found->tier());
        $this->assertSame(Platform::EUW1, $found->platform());
        $this->assertCount(2, $found->entries());
        $this->assertSame(['puuid-1', 'puuid-2'], array_map(fn ($e) => $e->puuid(), $found->entries()));
    }

    public function testSaveOverwritesExistingLeagueEntries(): void
    {
        // Given: initial save with 2 entries
        $this->repository->save($this->makeLeague(LeagueTier::CHALLENGER, Platform::EUW1, [
            ['old-puuid-1', 1000],
            ['old-puuid-2', 900],
        ]));

        // When: refresh with new entries
        $this->repository->save($this->makeLeague(LeagueTier::CHALLENGER, Platform::EUW1, [
            ['new-puuid-1', 2000],
        ]));

        $found = $this->repository->findByTierQueuePlatform(LeagueTier::CHALLENGER, Queue::RANKED_SOLO, Platform::EUW1);

        // Then: only new entries remain
        $this->assertNotNull($found);
        $this->assertCount(1, $found->entries());
        $this->assertSame('new-puuid-1', $found->entries()[0]->puuid());
    }

    public function testFindReturnsNullWhenNotFound(): void
    {
        $found = $this->repository->findByTierQueuePlatform(LeagueTier::MASTER, Queue::RANKED_SOLO, Platform::NA1);

        $this->assertNull($found);
    }

    public function testLeaguesAreIsolatedByTier(): void
    {
        // Given
        $this->repository->save($this->makeLeague(LeagueTier::CHALLENGER, Platform::EUW1, [['chall-puuid', 1500]]));
        $this->repository->save($this->makeLeague(LeagueTier::GRANDMASTER, Platform::EUW1, [['gm-puuid', 700]]));

        // When
        $challenger = $this->repository->findByTierQueuePlatform(LeagueTier::CHALLENGER, Queue::RANKED_SOLO, Platform::EUW1);
        $grandmaster = $this->repository->findByTierQueuePlatform(LeagueTier::GRANDMASTER, Queue::RANKED_SOLO, Platform::EUW1);

        // Then
        $this->assertNotNull($challenger);
        $this->assertNotNull($grandmaster);
        $this->assertSame('chall-puuid', $challenger->entries()[0]->puuid());
        $this->assertSame('gm-puuid', $grandmaster->entries()[0]->puuid());
    }

    public function testLeaguesAreIsolatedByPlatform(): void
    {
        // Given
        $this->repository->save($this->makeLeague(LeagueTier::CHALLENGER, Platform::EUW1, [['euw-puuid', 1000]]));
        $this->repository->save($this->makeLeague(LeagueTier::CHALLENGER, Platform::NA1, [['na-puuid', 900]]));

        // When / Then
        $euw = $this->repository->findByTierQueuePlatform(LeagueTier::CHALLENGER, Queue::RANKED_SOLO, Platform::EUW1);
        $na = $this->repository->findByTierQueuePlatform(LeagueTier::CHALLENGER, Queue::RANKED_SOLO, Platform::NA1);

        $this->assertSame('euw-puuid', $euw?->entries()[0]->puuid());
        $this->assertSame('na-puuid', $na?->entries()[0]->puuid());
    }

    public function testEntryDataIsPersisted(): void
    {
        // Given
        $league = League::create(
            LeagueTier::CHALLENGER,
            Queue::RANKED_SOLO,
            Platform::EUW1,
            1500,
            [LeagueEntry::create('puuid-x', 1500, 120, 80, null, true, false, true)],
            new \DateTimeImmutable('2026-01-01 00:00:00'),
        );

        // When
        $this->repository->save($league);
        $found = $this->repository->findByTierQueuePlatform(LeagueTier::CHALLENGER, Queue::RANKED_SOLO, Platform::EUW1);

        // Then
        $this->assertNotNull($found);
        $entry = $found->entries()[0];
        $this->assertSame('puuid-x', $entry->puuid());
        $this->assertSame(1500, $entry->leaguePoints());
        $this->assertSame(120, $entry->wins());
        $this->assertSame(80, $entry->losses());
        $this->assertTrue($entry->hotStreak());
        $this->assertFalse($entry->veteran());
        $this->assertTrue($entry->freshBlood());
    }

    /**
     * @param array<array{string, int}> $entries
     */
    private function makeLeague(LeagueTier $tier, Platform $platform, array $entries): League
    {
        $leagueEntries = array_map(
            static fn (array $e): LeagueEntry => LeagueEntry::create($e[0], $e[1], 10, 5, null, false, false, false),
            $entries,
        );

        return League::create($tier, Queue::RANKED_SOLO, $platform, 0, $leagueEntries, new \DateTimeImmutable());
    }
}
