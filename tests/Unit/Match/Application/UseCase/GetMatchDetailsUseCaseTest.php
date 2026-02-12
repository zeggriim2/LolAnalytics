<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\UseCase;

use App\Match\Application\Query\GetMatchByIdQuery;
use App\Match\Application\UseCase\GetMatchDetailsUseCase;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerPuuid;
use App\SharedContext\Application\Bus\QueryBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use PHPUnit\Framework\TestCase;

final class GetMatchDetailsUseCaseTest extends TestCase
{
    private QueryBusInterface $queryBus;
    private GetMatchDetailsUseCase $useCase;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBusInterface::class);
        $this->useCase = new GetMatchDetailsUseCase($this->queryBus);
    }

    private function createMatch(string $matchId): Matche
    {
        $participant = new Participant(
            summonerPuuid: SummonerPuuid::fromString('summoner123'),
            puuid: 'puuid-123',
            championId: 157,
            win: true,
            kda: new KDA(10, 5, 15),
            items: ['item1', 'item2']
        );

        return new Matche(
            id: MatchId::fromString($matchId),
            gameId: GameId::fromInt(1234567890),
            playedAt: new \DateTimeImmutable('2024-01-15 14:30:00'),
            durationSeconds: 1800,
            gameMode: 'gameMode',
            gameType: 'gameType',
            queueId: 2,
            mapId: 1,
            platform: Platform::EUW1,
            participants: [$participant]
        );
    }

    public function testExecuteReturnsMatchFromQueryBus(): void
    {
        $matchId = 'EUW1_1234567890';
        $expectedMatch = $this->createMatch($matchId);

        $this->queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(function ($query) use ($matchId) {
                return $query instanceof GetMatchByIdQuery
                    && $query->id === $matchId;
            }))
            ->willReturn($expectedMatch);

        $result = $this->useCase->execute($matchId);

        $this->assertSame($expectedMatch, $result);
    }

    public function testExecuteReturnsNullWhenMatchNotFound(): void
    {
        $matchId = 'EUW1_NONEXISTENT';

        $this->queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(function ($query) use ($matchId) {
                return $query instanceof GetMatchByIdQuery
                    && $query->id === $matchId;
            }))
            ->willReturn(null);

        $result = $this->useCase->execute($matchId);

        $this->assertNull($result);
    }

    public function testExecuteDispatchesCorrectQueryWithCorrectId(): void
    {
        $matchId = 'KR_9876543210';

        $this->queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(function ($query) use ($matchId) {
                return $query instanceof GetMatchByIdQuery
                    && $query->id === $matchId;
            }))
            ->willReturn(null);

        $this->useCase->execute($matchId);
    }
}
