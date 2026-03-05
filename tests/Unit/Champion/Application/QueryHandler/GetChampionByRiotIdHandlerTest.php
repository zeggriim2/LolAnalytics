<?php

declare(strict_types=1);

namespace App\Tests\Unit\Champion\Application\QueryHandler;

use App\Champion\Application\Query\GetChampionByRiotIdQuery;
use App\Champion\Application\QueryHandler\GetChampionByRiotIdHandler;
use App\Champion\Application\ReadModel\ChampionDetailReadModel;
use App\Champion\Domain\Model\Champion;
use App\Champion\Domain\Model\ChampionImage;
use App\Champion\Domain\Model\ChampionInfo;
use App\Champion\Domain\Model\ChampionStats;
use App\Champion\Domain\Repository\ChampionRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class GetChampionByRiotIdHandlerTest extends TestCase
{
    private ChampionRepositoryInterface $repository;
    private GetChampionByRiotIdHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ChampionRepositoryInterface::class);
        $this->handler = new GetChampionByRiotIdHandler($this->repository);
    }

    public function testGetChampionByRiotIdWithoutVersion(): void
    {
        $query = new GetChampionByRiotIdQuery('Aatrox');
        $champion = $this->createChampion('Aatrox');

        $this->repository
            ->expects($this->once())
            ->method('findByRiotId')
            ->with('Aatrox')
            ->willReturn($champion);

        $this->repository
            ->expects($this->never())
            ->method('findByRiotIdAndVersion');

        $result = ($this->handler)($query);

        self::assertInstanceOf(ChampionDetailReadModel::class, $result);
        self::assertSame('Aatrox', $result->riotId);
        self::assertSame('15.1.1', $result->version);
    }

    public function testGetChampionByRiotIdWithVersion(): void
    {
        $query = new GetChampionByRiotIdQuery('Aatrox', '15.1.1');
        $champion = $this->createChampion('Aatrox');

        $this->repository
            ->expects($this->once())
            ->method('findByRiotIdAndVersion')
            ->with('Aatrox', '15.1.1')
            ->willReturn($champion);

        $this->repository
            ->expects($this->never())
            ->method('findByRiotId');

        $result = ($this->handler)($query);

        self::assertInstanceOf(ChampionDetailReadModel::class, $result);
        self::assertSame('Aatrox', $result->riotId);
    }

    public function testReturnsNullWhenChampionNotFound(): void
    {
        $query = new GetChampionByRiotIdQuery('NonExistent');

        $this->repository
            ->expects($this->once())
            ->method('findByRiotId')
            ->willReturn(null);

        $result = ($this->handler)($query);

        self::assertNull($result);
    }

    public function testReturnsNullWhenChampionNotFoundForVersion(): void
    {
        $query = new GetChampionByRiotIdQuery('Aatrox', '99.99.99');

        $this->repository
            ->expects($this->once())
            ->method('findByRiotIdAndVersion')
            ->willReturn(null);

        $result = ($this->handler)($query);

        self::assertNull($result);
    }

    public function testReturnedReadModelContainsImageData(): void
    {
        $query = new GetChampionByRiotIdQuery('Aatrox');
        $champion = $this->createChampion('Aatrox');

        $this->repository
            ->expects($this->once())
            ->method('findByRiotId')
            ->willReturn($champion);

        $result = ($this->handler)($query);

        self::assertNotNull($result);
        self::assertSame('Aatrox.png', $result->image['full']);
        self::assertSame('champion0.png', $result->image['sprite']);
        self::assertSame('champion', $result->image['group']);
        self::assertSame(0, $result->image['x']);
        self::assertSame(48, $result->image['w']);
    }

    public function testReturnedReadModelContainsInfoAndStats(): void
    {
        $query = new GetChampionByRiotIdQuery('Aatrox');
        $champion = $this->createChampion('Aatrox');

        $this->repository
            ->expects($this->once())
            ->method('findByRiotId')
            ->willReturn($champion);

        $result = ($this->handler)($query);

        self::assertNotNull($result);
        self::assertSame(8, $result->info['attack']);
        self::assertSame(4, $result->info['defense']);
        self::assertSame(580.0, $result->stats['hp']);
        self::assertSame(345.0, $result->stats['moveSpeed']);
    }

    private function createChampion(string $riotId): Champion
    {
        return Champion::create(
            riotId: $riotId,
            version: '15.1.1',
            championKey: '266',
            name: $riotId,
            title: 'the Darkin Blade',
            blurb: 'Once honored defenders...',
            partype: 'Blood Well',
            tags: ['Fighter', 'Tank'],
            image: new ChampionImage(
                'Aatrox.png',
                'champion0.png',
                'champion',
                0,
                0,
                48,
                48
            ),
            info: new ChampionInfo(
                8,
                4,
                3,
                4
            ),
            stats: new ChampionStats(
                580.0,
                90.0,
                0.0,
                0.0,
                345.0,
                38.0,
                3.25,
                32.0,
                1.25,
                175.0,
                3.0,
                1.0,
                0.0,
                0.0,
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
