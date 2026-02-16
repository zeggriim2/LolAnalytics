<?php

declare(strict_types=1);

namespace App\Tests\Unit\Champion\Domain\Model;

use App\Champion\Domain\Model\Champion;
use App\Champion\Domain\Model\ChampionImage;
use App\Champion\Domain\Model\ChampionInfo;
use App\Champion\Domain\Model\ChampionStats;
use PHPUnit\Framework\TestCase;

final class ChampionTest extends TestCase
{
    public function testCanCreateChampion(): void
    {
        $champion = $this->createValidChampion();

        self::assertInstanceOf(Champion::class, $champion);
        self::assertSame('Aatrox', $champion->riotId());
        self::assertSame('15.1.1', $champion->version());
        self::assertSame('266', $champion->championKey());
        self::assertSame('Aatrox', $champion->name());
        self::assertSame('the Darkin Blade', $champion->title());
        self::assertSame('Once honored defenders...', $champion->blurb());
        self::assertSame('Blood Well', $champion->partype());
        self::assertSame(['Fighter', 'Tank'], $champion->tags());
        self::assertInstanceOf(ChampionImage::class, $champion->image());
        self::assertSame('Aatrox.png', $champion->image()->full());
        self::assertInstanceOf(ChampionInfo::class, $champion->info());
        self::assertSame(8, $champion->info()->attack());
        self::assertInstanceOf(ChampionStats::class, $champion->stats());
        self::assertSame(580.0, $champion->stats()->hp());
    }

    public function testThrowsExceptionWhenRiotIdIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('riotId must not be empty');

        $this->createChampionWithOverrides(riotId: '');
    }

    public function testThrowsExceptionWhenVersionIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('version must not be empty');

        $this->createChampionWithOverrides(version: '');
    }

    public function testThrowsExceptionWhenChampionKeyIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('championKey must not be empty');

        $this->createChampionWithOverrides(championKey: '');
    }

    public function testThrowsExceptionWhenNameIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name must not be empty');

        $this->createChampionWithOverrides(name: '');
    }

    public function testCanCreateWithEmptyTags(): void
    {
        $champion = $this->createChampionWithOverrides(tags: []);

        self::assertSame([], $champion->tags());
    }

    public function testCanCreateWithEmptyBlurb(): void
    {
        $champion = $this->createChampionWithOverrides(blurb: '');

        self::assertSame('', $champion->blurb());
    }

    private function createValidChampion(): Champion
    {
        return $this->createChampionWithOverrides();
    }

    private function createChampionWithOverrides(
        string $riotId = 'Aatrox',
        string $version = '15.1.1',
        string $championKey = '266',
        string $name = 'Aatrox',
        string $title = 'the Darkin Blade',
        string $blurb = 'Once honored defenders...',
        string $partype = 'Blood Well',
        array $tags = ['Fighter', 'Tank'],
    ): Champion {
        return Champion::create(
            riotId: $riotId,
            version: $version,
            championKey: $championKey,
            name: $name,
            title: $title,
            blurb: $blurb,
            partype: $partype,
            tags: $tags,
            image: new ChampionImage(
                full: 'Aatrox.png',
                sprite: 'champion0.png',
                group: 'champion',
                x: 0,
                y: 0,
                w: 48,
                h: 48,
            ),
            info: new ChampionInfo(
                attack: 8,
                defense: 4,
                magic: 3,
                difficulty: 4
            ),
            stats: new ChampionStats(
                hp: 580.0,
                hpPerLevel: 90.0,
                mp: 0.0,
                mpPerLevel: 0.0,
                moveSpeed: 345.0,
                armor: 38.0,
                armorPerLevel: 3.25,
                spellBlock: 32.0,
                spellBlockPerLevel: 1.25,
                attackRange: 175.0,
                hpRegen: 3.0,
                hpRegenPerLevel: 1.0,
                mpRegen: 0.0,
                mpRegenPerLevel: 0.0,
                crit: 0.0,
                critPerLevel: 0.0,
                attackDamage: 60.0,
                attackDamagePerLevel: 5.0,
                attackSpeed: 0.651,
                attackSpeedPerLevel: 2.5,
            ),
        );
    }
}
