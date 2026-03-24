<?php

declare(strict_types=1);

namespace App\Tests\Functional\Match\Application\QueryHandler;

use App\Match\Application\Filter\MatchFilters;
use App\Match\Application\Filter\Specification\DateFromSpecification;
use App\Match\Application\Filter\Specification\DateToSpecification;
use App\Match\Application\Filter\Specification\GameModeSpecification;
use App\Match\Application\Filter\Specification\PlatformSpecification;
use App\Match\Application\Filter\Specification\VersionSpecification;
use App\Match\Application\Query\ListMatchesQuery;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\SharedContext\Domain\Pagination\PaginationRequest;
use App\Tests\Factory\GameModeEntityFactory;
use App\Tests\Factory\MatchEntityFactory;
use App\Tests\Factory\VersionEntityFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ListMatchesHandlerTest extends KernelTestCase
{
    use ResetDatabase;

    private MessageBusInterface $queryBus;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();
        $this->queryBus = $container->get('query.bus');
    }

    public function testListMatchesWithEmptyDatabase(): void
    {
        // Given: empty database
        // When: listing matches
        $envelope = $this->queryBus->dispatch(new ListMatchesQuery());
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return empty paginated result
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(0, $result->items);
        $this->assertSame(0, $result->total);
    }

    public function testListMatchesWithSingleMatch(): void
    {
        // Given: one match in database
        MatchEntityFactory::createOne([
            'matchId' => 'EUW1_1234567890',
            'region' => 'EUW1',
        ]);

        // When: listing matches
        $envelope = $this->queryBus->dispatch(new ListMatchesQuery());
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return one match
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(1, $result->items);
        $this->assertSame(1, $result->total);
        $this->assertSame('EUW1_1234567890', $result->items[0]->id);
    }

    public function testListMatchesWithMultipleMatches(): void
    {
        // Given: multiple matches in database
        MatchEntityFactory::createMany(5, [
            'region' => 'EUW1',
        ]);

        // When: listing matches
        $envelope = $this->queryBus->dispatch(new ListMatchesQuery());
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return all matches
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(5, $result->items);
        $this->assertSame(5, $result->total);
    }

    public function testListMatchesReturnsMatchesWithParticipants(): void
    {
        // Given: a match with participants
        MatchEntityFactory::createOne([
            'matchId' => 'EUW1_9999999999',
            'region' => 'EUW1',
        ]);

        // When: listing matches
        $envelope = $this->queryBus->dispatch(new ListMatchesQuery());
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return match with participants
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(1, $result->items);
        $match = $result->items[0];
        $this->assertSame(10, $match->participantsCount, 'Match should have 10 participants by default');
    }

    public function testListMatchesReturnsCorrectDomainModels(): void
    {
        // Given: a match with specific data
        $playedAt = new \DateTime('2024-01-15 14:30:00');
        $version = VersionEntityFactory::createOne(['version' => '15.3.1']);
        MatchEntityFactory::createOne([
            'matchId' => 'EUW1_111111111',
            'gameId' => 123456789,
            'region' => 'EUW1',
            'playedAt' => $playedAt,
            'durationSeconds' => 1800,
            'version' => $version,
        ]);

        // When: listing matches
        $envelope = $this->queryBus->dispatch(new ListMatchesQuery());
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return correct domain model
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(1, $result->items);
        $match = $result->items[0];
        $this->assertSame('EUW1_111111111', $match->id);
        $this->assertSame(123456789, $match->gameId);
        $this->assertSame(1800, $match->durationSeconds);
        $this->assertSame(
            $playedAt->format('Y-m-d H:i:s'),
            $match->playedAt->format('Y-m-d H:i:s')
        );
    }

    public function testListMatchesWithPagination(): void
    {
        // Given: 5 matches in database
        MatchEntityFactory::createMany(5, [
            'region' => 'EUW1',
        ]);

        // When: listing with limit 2, page 1
        $envelope = $this->queryBus->dispatch(
            new ListMatchesQuery(new PaginationRequest(1, 2))
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return 2 items with total of 5
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(2, $result->items);
        $this->assertSame(5, $result->total);
        $this->assertSame(1, $result->page);
        $this->assertSame(3, $result->totalPages);
    }

    public function testFilterByPlatform(): void
    {
        // Given: matches on different platforms
        MatchEntityFactory::createMany(3, ['platform' => 'euw1']);
        MatchEntityFactory::createMany(2, ['platform' => 'na1']);

        // When: filtering by euw1
        $envelope = $this->queryBus->dispatch(
            new ListMatchesQuery(filters: new MatchFilters(new PlatformSpecification('euw1')))
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return only euw1 matches
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(3, $result->items);
        $this->assertSame(3, $result->total);

        foreach ($result->items as $item) {
            $this->assertSame('euw1', $item->platform);
        }
    }

    public function testFilterByGameMode(): void
    {
        // Given: matches with different game modes
        $classic = GameModeEntityFactory::new()->classic()->create();
        $aram = GameModeEntityFactory::new()->aram()->create();

        MatchEntityFactory::createMany(4, ['gameMode' => $classic]);
        MatchEntityFactory::createMany(2, ['gameMode' => $aram]);

        // When: filtering by CLASSIC
        $envelope = $this->queryBus->dispatch(
            new ListMatchesQuery(filters: new MatchFilters(new GameModeSpecification('CLASSIC')))
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return only CLASSIC matches
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(4, $result->items);
        $this->assertSame(4, $result->total);

        foreach ($result->items as $item) {
            $this->assertSame('CLASSIC', $item->gameMode);
        }
    }

    public function testFilterByVersion(): void
    {
        // Given: matches with different versions
        $v14 = VersionEntityFactory::createOne(['version' => '14.6.1']);
        $v15 = VersionEntityFactory::createOne(['version' => '15.1.1']);

        MatchEntityFactory::createMany(3, ['version' => $v14]);
        MatchEntityFactory::createMany(2, ['version' => $v15]);

        // When: filtering by version prefix 14.6
        $envelope = $this->queryBus->dispatch(
            new ListMatchesQuery(filters: new MatchFilters(new VersionSpecification('14.6')))
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return only 14.6.x matches
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(3, $result->items);
        $this->assertSame(3, $result->total);
    }

    public function testFilterByDateFrom(): void
    {
        // Given: matches at different dates
        MatchEntityFactory::createOne(['playedAt' => new \DateTime('2024-01-10')]);
        MatchEntityFactory::createOne(['playedAt' => new \DateTime('2024-03-15')]);
        MatchEntityFactory::createOne(['playedAt' => new \DateTime('2024-06-20')]);

        // When: filtering from 2024-03-01
        $envelope = $this->queryBus->dispatch(
            new ListMatchesQuery(filters: new MatchFilters(
                new DateFromSpecification(new \DateTimeImmutable('2024-03-01 00:00:00'))
            ))
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return 2 matches on or after 2024-03-01
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(2, $result->items);
        $this->assertSame(2, $result->total);
    }

    public function testFilterByDateTo(): void
    {
        // Given: matches at different dates
        MatchEntityFactory::createOne(['playedAt' => new \DateTime('2024-01-10')]);
        MatchEntityFactory::createOne(['playedAt' => new \DateTime('2024-03-15')]);
        MatchEntityFactory::createOne(['playedAt' => new \DateTime('2024-06-20')]);

        // When: filtering up to 2024-03-31
        $envelope = $this->queryBus->dispatch(
            new ListMatchesQuery(filters: new MatchFilters(
                new DateToSpecification(new \DateTimeImmutable('2024-03-31 23:59:59'))
            ))
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return 2 matches on or before 2024-03-31
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(2, $result->items);
        $this->assertSame(2, $result->total);
    }

    public function testFilterByDateRange(): void
    {
        // Given: matches spread across the year
        MatchEntityFactory::createOne(['playedAt' => new \DateTime('2024-01-10')]);
        MatchEntityFactory::createOne(['playedAt' => new \DateTime('2024-04-05')]);
        MatchEntityFactory::createOne(['playedAt' => new \DateTime('2024-07-20')]);
        MatchEntityFactory::createOne(['playedAt' => new \DateTime('2024-11-01')]);

        // When: filtering between 2024-03-01 and 2024-08-31
        $envelope = $this->queryBus->dispatch(
            new ListMatchesQuery(filters: new MatchFilters(
                new DateFromSpecification(new \DateTimeImmutable('2024-03-01 00:00:00')),
                new DateToSpecification(new \DateTimeImmutable('2024-08-31 23:59:59')),
            ))
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return only the 2 matches within range
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(2, $result->items);
        $this->assertSame(2, $result->total);
    }

    public function testCombinedFilters(): void
    {
        // Given: matches with various combinations
        $classic = GameModeEntityFactory::new()->classic()->create();
        $aram = GameModeEntityFactory::new()->aram()->create();

        MatchEntityFactory::createMany(2, ['platform' => 'euw1', 'gameMode' => $classic]);
        MatchEntityFactory::createMany(2, ['platform' => 'euw1', 'gameMode' => $aram]);
        MatchEntityFactory::createMany(2, ['platform' => 'na1', 'gameMode' => $classic]);

        // When: filtering by euw1 + CLASSIC
        $envelope = $this->queryBus->dispatch(
            new ListMatchesQuery(filters: new MatchFilters(
                new PlatformSpecification('euw1'),
                new GameModeSpecification('CLASSIC'),
            ))
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return only euw1 CLASSIC matches
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(2, $result->items);
        $this->assertSame(2, $result->total);
    }

    public function testFilterWithNoMatchingResults(): void
    {
        // Given: matches on euw1 only
        MatchEntityFactory::createMany(3, ['platform' => 'euw1']);

        // When: filtering by kr (no matches)
        $envelope = $this->queryBus->dispatch(
            new ListMatchesQuery(filters: new MatchFilters(new PlatformSpecification('kr')))
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return empty result
        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(0, $result->items);
        $this->assertSame(0, $result->total);
    }
}
