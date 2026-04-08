<?php

declare(strict_types=1);

namespace App\Tests\Unit\League\Application\QueryHandler;

use App\League\Application\Query\GetLeaguePuuidsQuery;
use App\League\Application\QueryHandler\GetLeaguePuuidsHandler;
use App\League\Domain\Enum\LeagueTier;
use App\League\Domain\Model\League;
use App\League\Domain\Model\LeagueEntry;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use PHPUnit\Framework\TestCase;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final class GetLeaguePuuidsHandlerTest extends TestCase
{
    public function testReturnsPuuidsFromStoredLeague(): void
    {
        // Given
        $league = League::create(
            LeagueTier::CHALLENGER,
            Queue::RANKED_SOLO,
            Platform::EUW1,
            0,
            [
                LeagueEntry::create('puuid-1', 1000, 10, 5, null, false, false, false),
                LeagueEntry::create('puuid-2', 500, 8, 3, null, false, false, false),
            ],
            new \DateTimeImmutable(),
        );

        $repository = $this->createStub(LeagueRepositoryInterface::class);
        $repository->method('findByTierQueuePlatform')->willReturn($league);

        // When
        $result = (new GetLeaguePuuidsHandler($repository))(
            new GetLeaguePuuidsQuery(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO),
        );

        // Then
        $this->assertSame(['puuid-1', 'puuid-2'], $result);
    }

    public function testReturnsEmptyArrayWhenLeagueNotFound(): void
    {
        $repository = $this->createStub(LeagueRepositoryInterface::class);
        $repository->method('findByTierQueuePlatform')->willReturn(null);

        $result = (new GetLeaguePuuidsHandler($repository))(
            new GetLeaguePuuidsQuery(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO),
        );

        $this->assertSame([], $result);
    }
}
