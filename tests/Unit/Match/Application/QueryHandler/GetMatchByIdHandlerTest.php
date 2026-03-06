<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\QueryHandler;

use App\Match\Application\Port\SummonerAdapterInterface;
use App\Match\Application\Query\GetMatchByIdQuery;
use App\Match\Application\QueryHandler\GetMatchByIdHandler;
use App\Match\Application\ReadModel\MatchDetailReadModel;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerPuuid;
use App\SharedContext\Domain\ValueObjet\Platform;
use PHPUnit\Framework\TestCase;

final class GetMatchByIdHandlerTest extends TestCase
{
    private MatchRepositoryInterface $repository;
    private SummonerAdapterInterface $summonerAdapter;
    private GetMatchByIdHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MatchRepositoryInterface::class);
        $this->summonerAdapter = $this->createMock(SummonerAdapterInterface::class);
        $this->handler = new GetMatchByIdHandler($this->repository, $this->summonerAdapter);
    }

    public function testReturnsMatchWhenFound(): void
    {
        $matchId = 'EUW1_1234567890';
        $query = new GetMatchByIdQuery($matchId);

        $participant = new Participant(
            summonerPuuid: SummonerPuuid::fromString('summoner123'),
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
            gameMode: 'gameMode',
            gameType: 'gameType',
            queueId: 1,
            mapId: 1,
            version: '16.3',
            platform: Platform::EUW1,
            participants: [$participant]
        );

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($matchId)
            ->willReturn($expectedMatch);

        $this->summonerAdapter
            ->expects($this->once())
            ->method('findGameNamesByPuuids')
            ->with([$participant->summonerPuuid()])
            ->willReturn([(string) $participant->summonerPuuid() => 'gameName']);

        $result = ($this->handler)($query);

        $this->assertInstanceOf(MatchDetailReadModel::class, $result);
        $this->assertSame($matchId, $result->id);
        $this->assertSame(1234567890, $result->gameId);
        $this->assertSame(1800, $result->durationSeconds);
        $this->assertCount(1, $result->participants);
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

        $this->summonerAdapter
            ->expects($this->never())
            ->method('findGameNamesByPuuids');

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

        $this->summonerAdapter
            ->expects($this->never())
            ->method('findGameNamesByPuuids')
            ->with([])
            ->willReturn([]);

        ($this->handler)($query);
    }
}
