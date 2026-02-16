<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\QueryHandler;

use App\Match\Application\Query\ListMatchesQuery;
use App\Match\Application\QueryHandler\ListMatchesHandler;
use App\Match\Application\ReadModel\MatchReadModel;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerPuuid;
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
            items: []
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
            version: "16.3",
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
            ->method('findAll')
            ->willReturn($matches);

        $result = ($this->handler)($query);

        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf(MatchReadModel::class, $result);
        $this->assertSame('EUW1_1234567890', $result[0]->id);
        $this->assertSame('EUW1_1234567891', $result[1]->id);
        $this->assertSame('EUW1_1234567892', $result[2]->id);
    }

    public function testReturnsEmptyArrayWhenNoMatches(): void
    {
        $query = new ListMatchesQuery();

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $result = ($this->handler)($query);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testReturnsSingleMatch(): void
    {
        $query = new ListMatchesQuery();

        $match = $this->createMatch('EUW1_SINGLE', 111111111);

        $this->repository
            ->method('findAll')
            ->willReturn([$match]);

        $result = ($this->handler)($query);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(MatchReadModel::class, $result[0]);
        $this->assertSame('EUW1_SINGLE', $result[0]->id);
    }

    public function testCallsRepositoryFindAll(): void
    {
        $query = new ListMatchesQuery();

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        ($this->handler)($query);
    }
}
