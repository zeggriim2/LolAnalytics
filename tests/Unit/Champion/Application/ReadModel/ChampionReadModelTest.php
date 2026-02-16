<?php

declare(strict_types=1);

namespace App\Tests\Unit\Champion\Application\ReadModel;

use App\Champion\Application\ReadModel\ChampionReadModel;
use App\Champion\Domain\Model\Champion;
use App\Champion\Domain\Model\ChampionImage;
use App\Champion\Domain\Model\ChampionInfo;
use App\Champion\Domain\Model\ChampionStats;
use PHPUnit\Framework\TestCase;

final class ChampionReadModelTest extends TestCase
{
    public function testFromDomainMapsCorrectly(): void
    {
        $champion = $this->createChampion();

        $readModel = ChampionReadModel::fromDomain($champion);

        self::assertSame('Aatrox', $readModel->riotId);
        self::assertSame('15.1.1', $readModel->version);
        self::assertSame('266', $readModel->championKey);
        self::assertSame('Aatrox', $readModel->name);
        self::assertSame('the Darkin Blade', $readModel->title);
        self::assertSame('Blood Well', $readModel->partype);
        self::assertSame(['Fighter', 'Tank'], $readModel->tags);
        self::assertSame('Aatrox.png', $readModel->imageFull);
    }

    public function testImageFullUsesImageValueObject(): void
    {
        $champion = Champion::create(
            riotId: 'Yasuo',
            version: '15.1.1',
            championKey: '157',
            name: 'Yasuo',
            title: 'the Unforgiven',
            blurb: '',
            partype: 'Flow',
            tags: ['Fighter', 'Assassin'],
            image: new ChampionImage(
                'Yasuo.png',
                'champion4.png',
                'champion',
                96,
                0,
                48,
                48
            ),
            info: new ChampionInfo(
                8,
                4,
                4,
                10
            ),
            stats: new ChampionStats(
                490.0,
                87.0,
                100.0,
                0.0,
                345.0,
                30.0,
                3.4,
                30.0,
                1.25,
                175.0,
                6.5,
                0.6,
                0.0,
                0.0,
                0.0,
                0.0,
                60.0,
                3.0,
                0.67,
                2.5,
            ),
        );

        $readModel = ChampionReadModel::fromDomain($champion);

        self::assertSame('Yasuo.png', $readModel->imageFull);
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
