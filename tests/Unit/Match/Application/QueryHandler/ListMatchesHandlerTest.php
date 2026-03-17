<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\QueryHandler;

use App\Match\Application\Query\ListMatchesQuery;
use App\Match\Application\QueryHandler\ListMatchesHandler;
use App\Match\Application\ReadModel\MatchReadModel;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\Model\ParticipantStats;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerPuuid;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\SharedContext\Domain\Pagination\PaginationRequest;
use App\SharedContext\Domain\ValueObjet\Platform;
use PHPUnit\Framework\TestCase;

final class ListMatchesHandlerTest extends TestCase
{
    private MatchRepositoryInterface $repository;
    private ListMatchesHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MatchRepositoryInterface::class);
        $this->handler = new ListMatchesHandler($this->repository);
    }

    private function createMatch(string $matchId, int $gameId): Matche
    {
        $participant = new Participant(
            summonerPuuid: SummonerPuuid::fromString('summoner-' . $matchId),
            puuid: 'puuid-' . $matchId,
            championId: 157,
            win: true,
            kda: new KDA(10, 5, 15),
            stats: ParticipantStats::empty(),
        );

        return new Matche(
            id: MatchId::fromString($matchId),
            gameId: GameId::fromInt($gameId),
            playedAt: new \DateTimeImmutable(),
            durationSeconds: 1800,
            gameMode: 'gameMode',
            gameType: 'gameType',
            queueId: 2,
            mapId: 1,
            version: '16.3',
            platform: Platform::EUW1,
            participants: [$participant]
        );
    }

    public function testReturnsAllMatches(): void
    {
        $query = new ListMatchesQuery();

        $matches = [
            $this->createMatch('EUW1_1234567890', 1234567890),
            $this->createMatch('EUW1_1234567891', 1234567891),
            $this->createMatch('EUW1_1234567892', 1234567892),
        ];

        $this->repository
            ->expects($this->once())
            ->method('findPaginated')
            ->with(0, 20)
            ->willReturn($matches);

        $this->repository
            ->expects($this->once())
            ->method('count')
            ->willReturn(3);

        $result = ($this->handler)($query);

        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(3, $result->items);
        $this->assertContainsOnlyInstancesOf(MatchReadModel::class, $result->items);
        $this->assertSame('EUW1_1234567890', $result->items[0]->id);
        $this->assertSame('EUW1_1234567891', $result->items[1]->id);
        $this->assertSame('EUW1_1234567892', $result->items[2]->id);
        $this->assertSame(3, $result->total);
    }

    public function testReturnsEmptyArrayWhenNoMatches(): void
    {
        $query = new ListMatchesQuery();

        $this->repository
            ->expects($this->once())
            ->method('findPaginated')
            ->willReturn([]);

        $this->repository
            ->expects($this->once())
            ->method('count')
            ->willReturn(0);

        $result = ($this->handler)($query);

        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertEmpty($result->items);
        $this->assertSame(0, $result->total);
    }

    public function testReturnsSingleMatch(): void
    {
        $query = new ListMatchesQuery();

        $match = $this->createMatch('EUW1_SINGLE', 111111111);

        $this->repository
            ->expects($this->once())
            ->method('findPaginated')
            ->willReturn([$match]);

        $this->repository
            ->expects($this->once())
            ->method('count')
            ->willReturn(1);

        $result = ($this->handler)($query);

        $this->assertCount(1, $result->items);
        $this->assertInstanceOf(MatchReadModel::class, $result->items[0]);
        $this->assertSame('EUW1_SINGLE', $result->items[0]->id);
    }

    public function testCallsRepositoryWithPaginationParams(): void
    {
        $query = new ListMatchesQuery(new PaginationRequest(2, 10));

        $this->repository
            ->expects($this->once())
            ->method('findPaginated')
            ->with(10, 10)
            ->willReturn([]);

        $this->repository
            ->expects($this->once())
            ->method('count')
            ->willReturn(0);

        ($this->handler)($query);
    }
}
