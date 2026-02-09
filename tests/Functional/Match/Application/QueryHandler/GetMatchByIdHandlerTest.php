<?php

declare(strict_types=1);

namespace App\Tests\Functional\Match\Application\QueryHandler;

use App\Match\Application\Query\GetMatchByIdQuery;
use App\SharedContext\Domain\ValueObjet\Platform;
use App\Tests\Factory\MatchEntityFactory;
use App\Tests\Factory\ParticipantEntityFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Zenstruck\Foundry\Test\ResetDatabase;

final class GetMatchByIdHandlerTest extends KernelTestCase
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

    public function testGetMatchByIdReturnsNullWhenMatchDoesNotExist(): void
    {
        // Given: empty database
        // When: getting a match by ID that doesn't exist
        $envelope = $this->queryBus->dispatch(new GetMatchByIdQuery('EUW1_NONEXISTENT'));
        $match = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return null
        $this->assertNull($match);
    }

    public function testGetMatchByIdReturnsMatchWhenExists(): void
    {
        // Given: a match in database
        MatchEntityFactory::createOne([
            'matchId' => 'EUW1_1234567890',
            'region' => Platform::EUW1->value,
        ]);

        // When: getting the match by ID
        $envelope = $this->queryBus->dispatch(new GetMatchByIdQuery('EUW1_1234567890'));
        $match = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return the match
        $this->assertNotNull($match);
        $this->assertSame('EUW1_1234567890', (string) $match->id());
    }

    public function testGetMatchByIdReturnsCorrectDomainModel(): void
    {
        // Given: a match with specific data
        $playedAt = new \DateTime('2024-01-15 14:30:00');
        MatchEntityFactory::createOne([
            'matchId' => 'EUW1_111111111',
            'gameId' => 987654321,
            'region' => 'EUW1',
            'playedAt' => $playedAt,
            'durationSeconds' => 2400,
        ]);

        // When: getting the match by ID
        $envelope = $this->queryBus->dispatch(new GetMatchByIdQuery('EUW1_111111111'));
        $match = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return correct domain model with all properties
        $this->assertNotNull($match);
        $this->assertSame('EUW1_111111111', (string) $match->id());
        $this->assertSame(987654321, $match->gameId()->value());
        $this->assertSame(2400, $match->durationSeconds());
        $this->assertSame(
            $playedAt->format('Y-m-d H:i:s'),
            $match->playedAt()->format('Y-m-d H:i:s')
        );
    }

    public function testGetMatchByIdReturnsMatchWithParticipants(): void
    {
        // Given: a match with participants
        MatchEntityFactory::createOne([
            'matchId' => 'EUW1_9999999999',
            'region' => 'EUW1',
        ]);

        // When: getting the match by ID
        $envelope = $this->queryBus->dispatch(new GetMatchByIdQuery('EUW1_9999999999'));
        $match = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return match with all participants
        $this->assertNotNull($match);
        $this->assertCount(10, $match->participants(), 'Match should have 10 participants by default');
    }

    public function testGetMatchByIdReturnsMatchWithCorrectParticipantData(): void
    {
        // Given: a match with specific participant data
        $matchEntity = MatchEntityFactory::new()->withoutParticipants()->create();

        ParticipantEntityFactory::createOne([
            'match' => $matchEntity->_real(),
            'puuid' => 'test-puuid-123',
            'summonerId' => 'summoner-123',
            'championId' => 157, // Yasuo
            'kills' => 10,
            'deaths' => 3,
            'assists' => 15,
            'win' => true,
        ]);

        // When: getting the match by ID
        $envelope = $this->queryBus->dispatch(
            new GetMatchByIdQuery($matchEntity->_real()->getMatchId())
        );
        $match = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return match with correct participant data
        $this->assertNotNull($match);
        $participants = $match->participants();
        $this->assertCount(1, $participants);

        $participant = $participants[0];
        $this->assertSame('test-puuid-123', $participant->puuid());
        $this->assertSame('summoner-123', (string) $participant->summonerId());
        $this->assertSame(157, $participant->championId());
        $this->assertTrue($participant->win());
        $this->assertSame(10, $participant->kda()->kills());
        $this->assertSame(3, $participant->kda()->deaths());
        $this->assertSame(15, $participant->kda()->assists());
    }

    public function testGetMatchByIdWithMultipleMatchesInDatabase(): void
    {
        // Given: multiple matches in database
        MatchEntityFactory::createMany(5);
        MatchEntityFactory::createOne([
            'matchId' => 'EUW1_TARGET_MATCH',
            'region' => 'EUW1',
        ]);

        // When: getting a specific match by ID
        $envelope = $this->queryBus->dispatch(new GetMatchByIdQuery('EUW1_TARGET_MATCH'));
        $match = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return only the requested match
        $this->assertNotNull($match);
        $this->assertSame('EUW1_TARGET_MATCH', (string) $match->id());
    }
}
