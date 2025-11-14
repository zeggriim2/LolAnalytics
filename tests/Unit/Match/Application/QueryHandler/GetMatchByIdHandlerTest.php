<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\QueryHandler;

use App\Match\Application\Query\GetMatchByIdQuery;
use App\Match\Application\QueryHandler\GetMatchByIdHandler;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerId;
use PHPUnit\Framework\TestCase;

final class GetMatchByIdHandlerTest extends TestCase
{
    private MatchRepositoryInterface $repository;
    private GetMatchByIdHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MatchRepositoryInterface::class);
        $this->handler = new GetMatchByIdHandler($this->repository);
    }

    public function testReturnsMatchWhenFound(): void
    {
        $matchId = 'EUW1_1234567890';
        $query = new GetMatchByIdQuery($matchId);

        $participant = new Participant(
            summonerId: SummonerId::fromString('summoner123'),
            puuid: 'puuid-123',
            championId: 157,
            win: true,
            kda: new KDA(10, 5, 15),
            items: []
        );

        $expectedMatch = new Matche(
            id: MatchId::fromString($matchId),
            gameId: GameId::fromInt(1234567890),
            playedAt: new \DateTimeImmutable('2024-01-15 14:30:00'),
            durationSeconds: 1800,
            participants: [$participant]
        );

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($matchId)
            ->willReturn($expectedMatch);

        $result = ($this->handler)($query);

        $this->assertSame($expectedMatch, $result);
    }

    public function testReturnsNullWhenMatchNotFound(): void
    {
        $matchId = 'EUW1_NONEXISTENT';
        $query = new GetMatchByIdQuery($matchId);

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($matchId)
            ->willReturn(null);

        $result = ($this->handler)($query);

        $this->assertNull($result);
    }

    public function testCallsRepositoryWithCorrectId(): void
    {
        $matchId = 'KR_9876543210';
        $query = new GetMatchByIdQuery($matchId);

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($this->identicalTo($matchId))
            ->willReturn(null);

        ($this->handler)($query);
    }
}
