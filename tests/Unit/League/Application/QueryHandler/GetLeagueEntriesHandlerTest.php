<?php

declare(strict_types=1);

namespace App\Tests\Unit\League\Application\QueryHandler;

use App\League\Application\Dto\LeagueEntryListDto;
use App\League\Application\Query\GetLeagueEntriesQuery;
use App\League\Application\QueryHandler\GetLeagueEntriesHandler;
use App\League\Domain\Enum\LeagueTier;
use App\League\Domain\Model\League;
use App\League\Domain\Model\LeagueEntry;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use PHPUnit\Framework\TestCase;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final class GetLeagueEntriesHandlerTest extends TestCase
{
    public function testReturnsEmptyResultWhenLeagueNotFound(): void
    {
        $repository = $this->createStub(LeagueRepositoryInterface::class);
        $repository->method('findByTierQueuePlatform')->willReturn(null);

        $result = (new GetLeagueEntriesHandler($repository))(
            new GetLeagueEntriesQuery(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO),
        );

        $this->assertSame(0, $result->total);
        $this->assertSame([], $result->items);
    }

    public function testEntriesAreSortedByLeaguePointsDescending(): void
    {
        $league = $this->makeLeague([
            ['puuid-low', 300],
            ['puuid-high', 1500],
            ['puuid-mid', 800],
        ]);

        $repository = $this->createStub(LeagueRepositoryInterface::class);
        $repository->method('findByTierQueuePlatform')->willReturn($league);

        $result = (new GetLeagueEntriesHandler($repository))(
            new GetLeagueEntriesQuery(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO),
        );

        $this->assertInstanceOf(LeagueEntryListDto::class, $result->items[0]);
        $this->assertSame('puuid-high', $result->items[0]->puuid);
        $this->assertSame('puuid-mid', $result->items[1]->puuid);
        $this->assertSame('puuid-low', $result->items[2]->puuid);
    }

    public function testRankReflectsSortedPosition(): void
    {
        $league = $this->makeLeague([
            ['puuid-a', 500],
            ['puuid-b', 1000],
        ]);

        $repository = $this->createStub(LeagueRepositoryInterface::class);
        $repository->method('findByTierQueuePlatform')->willReturn($league);

        $result = (new GetLeagueEntriesHandler($repository))(
            new GetLeagueEntriesQuery(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO),
        );

        $this->assertSame(1, $result->items[0]->rank); // puuid-b (1000 LP)
        $this->assertSame(2, $result->items[1]->rank); // puuid-a (500 LP)
    }

    public function testPaginationSlicesResults(): void
    {
        $entries = array_map(
            fn (int $i): array => ["puuid-{$i}", 1000 - $i * 10],
            range(0, 9),
        );
        $league = $this->makeLeague($entries);

        $repository = $this->createStub(LeagueRepositoryInterface::class);
        $repository->method('findByTierQueuePlatform')->willReturn($league);

        $result = (new GetLeagueEntriesHandler($repository))(
            new GetLeagueEntriesQuery(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO, page: 2, limit: 3),
        );

        $this->assertSame(10, $result->total);
        $this->assertCount(3, $result->items);
        $this->assertSame(4, $result->items[0]->rank); // page 2, offset 3
    }

    public function testTotalPagesCalculation(): void
    {
        $entries = array_map(fn (int $i): array => ["p-{$i}", $i * 10], range(1, 7));
        $league = $this->makeLeague($entries);

        $repository = $this->createStub(LeagueRepositoryInterface::class);
        $repository->method('findByTierQueuePlatform')->willReturn($league);

        $result = (new GetLeagueEntriesHandler($repository))(
            new GetLeagueEntriesQuery(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO, page: 1, limit: 3),
        );

        $this->assertSame(3, $result->totalPages); // ceil(7/3) = 3
    }

    /**
     * @param array<array{string, int}> $entries [puuid, lp]
     */
    private function makeLeague(array $entries): League
    {
        $leagueEntries = array_map(
            static fn (array $e): LeagueEntry => LeagueEntry::create($e[0], $e[1], 10, 5, null, false, false, false),
            $entries,
        );

        return League::create(LeagueTier::CHALLENGER, Queue::RANKED_SOLO, Platform::EUW1, 0, $leagueEntries, new \DateTimeImmutable());
    }
}
