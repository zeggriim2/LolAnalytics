<?php

declare(strict_types=1);

namespace App\Tests\Functional\Match\Application\QueryHandler;

use App\Match\Application\Query\ListMatchesQuery;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\SharedContext\Domain\Pagination\PaginationRequest;
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
}
