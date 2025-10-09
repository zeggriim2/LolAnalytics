<?php

declare(strict_types=1);

namespace App\Tests\Match\Application\QueryHandler;

use App\Match\Application\Query\GetMatchByIdQuery;
use App\Match\Application\QueryHandler\GetMatchByIdHandler;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerId;
use App\Tests\Doubles\Repository\InMemoryMatchRepository;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class GetMatchByIdHandlerTest extends TestCase
{
    public function testInvokeReturnsMatchFromRepository(): void
    {
        // Arrange
        $repository = new InMemoryMatchRepository();

        $participant = new Participant(
            SummonerId::fromString('summoner_1'),
            'puuid_1',
            300,
            true,
            new KDA(10, 2, 8),
            ['item1', 'item2']
        );

        $match = new Matche(
            MatchId::fromString('match_123'),
            GameId::fromInt(456),
            new DateTimeImmutable(),
            1800,
            [$participant]
        );

        $repository->save($match, 'europe');

        $handler = new GetMatchByIdHandler($repository);

        // Act
        $result = $handler(new GetMatchByIdQuery('match_123'));

        // Assert
        $this->assertInstanceOf(Matche::class, $result);
        $this->assertSame('match_123', (string)$result->id());
        $this->assertCount(1, $result->participants());
        $this->assertSame(1800, $result->durationSeconds());
    }

    public function testInvokeReturnsNullWhenNotFound(): void
    {
        // Arrange
        $repository = new InMemoryMatchRepository();
        $handler = new GetMatchByIdHandler($repository);

        // Act
        $result = $handler(new GetMatchByIdQuery('non_existing_match'));

        // Assert
        $this->assertNull($result);
    }
}
