<?php

declare(strict_types=1);

namespace App\Tests\Unit\League\Domain\Model;

use App\League\Domain\Enum\LeagueTier;
use App\League\Domain\Model\League;
use App\League\Domain\Model\LeagueEntry;
use App\SharedContext\Domain\ValueObjet\Platform;
use PHPUnit\Framework\TestCase;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final class LeagueTest extends TestCase
{
    public function testCreateReturnsCorrectValues(): void
    {
        $entries = [
            $this->makeEntry('puuid-1', 1500),
            $this->makeEntry('puuid-2', 800),
        ];
        $refreshedAt = new \DateTimeImmutable('2026-01-01 12:00:00');

        $league = League::create(
            tier: LeagueTier::CHALLENGER,
            queue: Queue::RANKED_SOLO,
            platform: Platform::EUW1,
            totalLp: 2300,
            entries: $entries,
            lastRefreshedAt: $refreshedAt,
        );

        $this->assertSame(LeagueTier::CHALLENGER, $league->tier());
        $this->assertSame(Queue::RANKED_SOLO, $league->queue());
        $this->assertSame(Platform::EUW1, $league->platform());
        $this->assertSame(2300, $league->totalLp());
        $this->assertCount(2, $league->entries());
        $this->assertSame($refreshedAt, $league->lastRefreshedAt());
    }

    public function testPuuidsExtractsAllPuuids(): void
    {
        $league = League::create(
            LeagueTier::GRANDMASTER,
            Queue::RANKED_SOLO,
            Platform::EUW1,
            0,
            [
                $this->makeEntry('puuid-a', 100),
                $this->makeEntry('puuid-b', 200),
                $this->makeEntry('puuid-c', 300),
            ],
            new \DateTimeImmutable(),
        );

        $puuids = $league->puuids();

        $this->assertSame(['puuid-a', 'puuid-b', 'puuid-c'], $puuids);
    }

    public function testPuuidsReturnsEmptyArrayWhenNoEntries(): void
    {
        $league = League::create(LeagueTier::MASTER, Queue::RANKED_SOLO, Platform::EUW1, 0, [], new \DateTimeImmutable());

        $this->assertSame([], $league->puuids());
    }

    private function makeEntry(string $puuid, int $lp): LeagueEntry
    {
        return LeagueEntry::create($puuid, $lp, 10, 5, null, false, false, false);
    }
}
