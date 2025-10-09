<?php

declare(strict_types=1);

namespace App\Tests\Match\Application\UseCase;

use App\Match\Application\Query\GetMatchByIdQuery;
use App\Match\Application\UseCase\GetMatchDetailsUseCase;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerId;
use App\SharedContext\Application\Bus\QueryBusInterface;
use PHPUnit\Framework\TestCase;

final class GetMatchDetailsUseCaseTest extends TestCase
{
    public function testExecuteResturnMatch(): void
    {
        $queryBus = $this->createMock(QueryBusInterface::class);

        $participant = new Participant(
            SummonerId::fromString('SummonerId_1'),
            'puuid_123',
            300,
            true,
            new KDA(10, 2, 8),
            []
        );

        $expectedMatch = new Matche(
            MatchId::fromString('122'),
            GameId::fromInt(345),
            new \DateTimeImmutable(),
            1542,
            [$participant]
        );

        // On s’assure que le bon Query est envoyé au bus
        $queryBus
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(function ($query) {
                return $query instanceof GetMatchByIdQuery
                    && '123' === $query->id;
            }))
            ->willReturn($expectedMatch);

        $useCase = new GetMatchDetailsUseCase($queryBus);

        // Act
        $result = $useCase->execute('123');

        // Assert
        $this->assertSame($expectedMatch, $result);
        $this->assertInstanceOf(Matche::class, $result);
        $this->assertEquals('122', (string) $result->id());
        $this->assertCount(1, $result->participants());
        $this->assertEquals(1542, $result->durationSeconds());
    }

    public function testExecuteReturnsNullWhenNotFound(): void
    {
        $queryBus = $this->createMock(QueryBusInterface::class);
        $queryBus
            ->expects($this->once())
            ->method('handle')
            ->willReturn(null);

        $useCase = new GetMatchDetailsUseCase($queryBus);

        $result = $useCase->execute('999');

        $this->assertNull($result);
    }
}
