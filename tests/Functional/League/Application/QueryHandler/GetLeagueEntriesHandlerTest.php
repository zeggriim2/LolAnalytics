<?php

declare(strict_types=1);

namespace App\Tests\Functional\League\Application\QueryHandler;

use App\League\Application\Query\GetLeagueEntriesQuery;
use App\League\Domain\Enum\LeagueTier;
use App\League\Domain\Model\League;
use App\League\Domain\Model\LeagueEntry;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use App\SharedContext\Application\Bus\QueryBusInterface;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\SharedContext\Domain\ValueObjet\Platform;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zeggriim\RiotApiDataDragon\Enum\Queue;
use Zenstruck\Foundry\Test\ResetDatabase;

final class GetLeagueEntriesHandlerTest extends KernelTestCase
{
    use ResetDatabase;

    private QueryBusInterface $queryBus;
    private LeagueRepositoryInterface $leagueRepository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $this->queryBus = $container->get(QueryBusInterface::class);
        $this->leagueRepository = $container->get(LeagueRepositoryInterface::class);
    }

    public function testReturnsEntriesSortedByLpFromDatabase(): void
    {
        // Given: league with 3 entries in random order
        $league = League::create(
            LeagueTier::CHALLENGER,
            Queue::RANKED_SOLO,
            Platform::EUW1,
            0,
            [
                LeagueEntry::create('low-puuid', 300, 10, 5, null, false, false, false),
                LeagueEntry::create('high-puuid', 1500, 100, 50, null, false, false, false),
                LeagueEntry::create('mid-puuid', 800, 60, 30, null, false, false, false),
            ],
            new \DateTimeImmutable(),
        );
        $this->leagueRepository->save($league);

        // When
        /** @var PaginatedResult<\App\League\Application\Dto\LeagueEntryListDto> $result */
        $result = $this->queryBus->handle(
            new GetLeagueEntriesQuery(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO),
        );

        // Then: sorted by LP desc
        $this->assertSame(3, $result->total);
        $this->assertSame('high-puuid', $result->items[0]->puuid);
        $this->assertSame(1, $result->items[0]->rank);
        $this->assertSame('mid-puuid', $result->items[1]->puuid);
        $this->assertSame('low-puuid', $result->items[2]->puuid);
    }

    public function testReturnsEmptyResultWhenNoLeagueInDatabase(): void
    {
        // When
        $result = $this->queryBus->handle(
            new GetLeagueEntriesQuery(Platform::EUW1, LeagueTier::CHALLENGER, Queue::RANKED_SOLO),
        );

        // Then
        $this->assertSame(0, $result->total);
        $this->assertSame([], $result->items);
    }
}
