<?php

declare(strict_types=1);

namespace App\Tests\Unit\Champion\Application\QueryHandler;

use App\Champion\Application\Query\ListChampionsQuery;
use App\Champion\Application\QueryHandler\ListChampionsHandler;
use App\Champion\Application\ReadModel\ChampionReadModel;
use App\Champion\Domain\Model\Champion;
use App\Champion\Domain\Model\ChampionImage;
use App\Champion\Domain\Model\ChampionInfo;
use App\Champion\Domain\Model\ChampionStats;
use App\Champion\Domain\Repository\ChampionRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class ListChampionsHandlerTest extends TestCase
{
    private ChampionRepositoryInterface $repository;
    private ListChampionsHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ChampionRepositoryInterface::class);
        $this->handler = new ListChampionsHandler($this->repository);
    }

    public function testListAllChampions(): void
    {
        $query = new ListChampionsQuery();

        $champions = [$this->createChampion('Aatrox'), $this->createChampion('Yasuo')];

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($champions);

        $this->repository
            ->expects($this->never())
            ->method('findByVersion');

        $result = ($this->handler)($query);

        self::assertCount(2, $result);
        self::assertContainsOnlyInstancesOf(ChampionReadModel::class, $result);
        self::assertSame('Aatrox', $result[0]->riotId);
        self::assertSame('Yasuo', $result[1]->riotId);
    }

    public function testListChampionsByVersion(): void
    {
        $query = new ListChampionsQuery('15.1.1');

        $champions = [$this->createChampion('Aatrox')];

        $this->repository
            ->expects($this->once())
            ->method('findByVersion')
            ->with('15.1.1')
            ->willReturn($champions);

        $this->repository
            ->expects($this->never())
            ->method('findAll');

        $result = ($this->handler)($query);

        self::assertCount(1, $result);
        self::assertSame('Aatrox', $result[0]->riotId);
    }

    public function testListReturnsEmptyArrayWhenNoChampions(): void
    {
        $query = new ListChampionsQuery();

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $result = ($this->handler)($query);

        self::assertSame([], $result);
    }

    private function createChampion(string $riotId): Champion
    {
        return Champion::create(
            riotId: $riotId,
            version: '15.1.1',
            championKey: '266',
            name: $riotId,
            title: 'Title',
            blurb: 'Blurb',
            partype: 'Mana',
            tags: ['Fighter'],
            image: new ChampionImage(
                'Full.png',
                'sprite.png',
                'champion',
                0,
                0,
                48,
                48
            ),
            info: new ChampionInfo(
                5,
                5,
                5,
                5
            ),
            stats: new ChampionStats(
                580.0,
                90.0,
                350.0,
                32.0,
                345.0,
                38.0,
                3.25,
                32.0,
                1.25,
                175.0,
                3.0,
                1.0,
                8.0,
                0.8,
                0.0,
                0.0,
                60.0,
                5.0,
                0.651,
                2.5,
            ),
        );
    }
}
