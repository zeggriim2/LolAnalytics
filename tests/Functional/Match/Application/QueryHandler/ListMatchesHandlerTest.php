<?php

declare(strict_types=1);

namespace App\Tests\Functional\Match\Application\QueryHandler;

use App\Match\Application\Query\ListMatchesQuery;
use App\Tests\Factory\MatchEntityFactory;
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
        $matches = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return empty array
        $this->assertIsArray($matches);
        $this->assertCount(0, $matches);
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
        $matches = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return one match
        $this->assertIsArray($matches);
        $this->assertCount(1, $matches);
        $this->assertSame('EUW1_1234567890', (string) $matches[0]->id());
    }

    public function testListMatchesWithMultipleMatches(): void
    {
        // Given: multiple matches in database
        MatchEntityFactory::createMany(5, [
            'region' => 'EUW1',
        ]);

        // When: listing matches
        $envelope = $this->queryBus->dispatch(new ListMatchesQuery());
        $matches = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return all matches
        $this->assertIsArray($matches);
        $this->assertCount(5, $matches);
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
        $matches = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return match with participants
        $this->assertCount(1, $matches);
        $match = $matches[0];
        $this->assertCount(10, $match->participants(), 'Match should have 10 participants by default');
    }

    public function testListMatchesReturnsCorrectDomainModels(): void
    {
        // Given: a match with specific data
        $playedAt = new \DateTime('2024-01-15 14:30:00');
        MatchEntityFactory::createOne([
            'matchId' => 'EUW1_111111111',
            'gameId' => 123456789,
            'region' => 'EUW1',
            'playedAt' => $playedAt,
            'durationSeconds' => 1800,
        ]);

        // When: listing matches
        $envelope = $this->queryBus->dispatch(new ListMatchesQuery());
        $matches = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return correct domain model
        $this->assertCount(1, $matches);
        $match = $matches[0];
        $this->assertSame('EUW1_111111111', (string) $match->id());
        $this->assertSame(123456789, $match->gameId()->value());
        $this->assertSame(1800, $match->durationSeconds());
        $this->assertSame(
            $playedAt->format('Y-m-d H:i:s'),
            $match->playedAt()->format('Y-m-d H:i:s')
        );
    }
}
