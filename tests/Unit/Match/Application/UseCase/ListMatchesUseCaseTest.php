<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\UseCase;

use App\Match\Application\Query\ListMatchesQuery;
use App\Match\Application\UseCase\ListMatchesUseCase;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerPuuid;
use App\SharedContext\Application\Bus\QueryBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use PHPUnit\Framework\TestCase;

final class ListMatchesUseCaseTest extends TestCase
{
    private QueryBusInterface $queryBus;
    private ListMatchesUseCase $useCase;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBusInterface::class);
        $this->useCase = new ListMatchesUseCase($this->queryBus);
    }

    private function createMatch(string $matchId): Matche
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
            gameId: GameId::fromInt(123456789),
            playedAt: new \DateTimeImmutable(),
            durationSeconds: 1800,
            gameMode: 'gameMode',
            gameType: 'gameType',
            queueId: 1,
            mapId: 900,
            version: '16.3',
            platform: Platform::EUW1,
            participants: [$participant]
        );
    }

    public function testExecuteReturnsMatchesFromQueryBus(): void
    {
        $matches = [
            $this->createMatch('EUW1_1234567890'),
            $this->createMatch('EUW1_1234567891'),
        ];

        $this->queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->isInstanceOf(ListMatchesQuery::class))
            ->willReturn($matches);

        $result = $this->useCase->execute();

        $this->assertSame($matches, $result);
        $this->assertCount(2, $result);
    }

    public function testExecuteReturnsEmptyArrayWhenNoMatches(): void
    {
        $this->queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->isInstanceOf(ListMatchesQuery::class))
            ->willReturn([]);

        $result = $this->useCase->execute();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testExecuteDispatchesCorrectQuery(): void
    {
        $this->queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(function ($query) {
                return $query instanceof ListMatchesQuery;
            }))
            ->willReturn([]);

        $this->useCase->execute();
    }
}
