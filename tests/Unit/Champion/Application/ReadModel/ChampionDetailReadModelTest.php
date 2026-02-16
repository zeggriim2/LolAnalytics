<?php

declare(strict_types=1);

namespace App\Tests\Unit\Champion\Application\ReadModel;

use App\Champion\Application\ReadModel\ChampionDetailReadModel;
use App\Champion\Domain\Model\Champion;
use App\Champion\Domain\Model\ChampionImage;
use App\Champion\Domain\Model\ChampionInfo;
use App\Champion\Domain\Model\ChampionStats;
use PHPUnit\Framework\TestCase;

final class ChampionDetailReadModelTest extends TestCase
{
    public function testFromDomainMapsAllFields(): void
    {
        $champion = $this->createChampion();

        $readModel = ChampionDetailReadModel::fromDomain($champion);

        self::assertSame('Aatrox', $readModel->riotId);
        self::assertSame('15.1.1', $readModel->version);
        self::assertSame('266', $readModel->championKey);
        self::assertSame('Aatrox', $readModel->name);
        self::assertSame('the Darkin Blade', $readModel->title);
        self::assertSame('Once honored defenders...', $readModel->blurb);
        self::assertSame('Blood Well', $readModel->partype);
        self::assertSame(['Fighter', 'Tank'], $readModel->tags);
    }

    public function testFromDomainMapsImageAsArray(): void
    {
        $readModel = ChampionDetailReadModel::fromDomain($this->createChampion());

        self::assertArrayHasKey('full', $readModel->image);
        self::assertArrayHasKey('sprite', $readModel->image);
        self::assertArrayHasKey('group', $readModel->image);
        self::assertArrayHasKey('x', $readModel->image);
        self::assertArrayHasKey('y', $readModel->image);
        self::assertArrayHasKey('w', $readModel->image);
        self::assertArrayHasKey('h', $readModel->image);

        self::assertSame('Aatrox.png', $readModel->image['full']);
        self::assertSame('champion0.png', $readModel->image['sprite']);
        self::assertSame('champion', $readModel->image['group']);
        self::assertSame(0, $readModel->image['x']);
        self::assertSame(0, $readModel->image['y']);
        self::assertSame(48, $readModel->image['w']);
        self::assertSame(48, $readModel->image['h']);
    }

    public function testFromDomainMapsInfoAsArray(): void
    {
        $readModel = ChampionDetailReadModel::fromDomain($this->createChampion());

        self::assertSame(8, $readModel->info['attack']);
        self::assertSame(4, $readModel->info['defense']);
        self::assertSame(3, $readModel->info['magic']);
        self::assertSame(4, $readModel->info['difficulty']);
    }

    public function testFromDomainMapsStatsAsArray(): void
    {
        $readModel = ChampionDetailReadModel::fromDomain($this->createChampion());

        self::assertSame(580.0, $readModel->stats['hp']);
        self::assertSame(90.0, $readModel->stats['hpPerLevel']);
        self::assertSame(345.0, $readModel->stats['moveSpeed']);
        self::assertSame(38.0, $readModel->stats['armor']);
        self::assertSame(60.0, $readModel->stats['attackDamage']);
        self::assertSame(0.651, $readModel->stats['attackSpeed']);
        self::assertCount(20, $readModel->stats);
    }

    private function createChampion(): Champion
    {
        return Champion::create(
            riotId: 'Aatrox',
            version: '15.1.1',
            championKey: '266',
            name: 'Aatrox',
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
