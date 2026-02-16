<?php

declare(strict_types=1);

namespace App\Tests\Unit\Champion\Domain\Model;

use App\Champion\Domain\Model\ChampionStats;
use PHPUnit\Framework\TestCase;

final class ChampionStatsTest extends TestCase
{
    public function testCanCreateChampionStats(): void
    {
        $stats = new ChampionStats(
            hp: 580.0,
            hpPerLevel: 90.0,
            mp: 350.0,
            mpPerLevel: 32.0,
            moveSpeed: 345.0,
            armor: 38.0,
            armorPerLevel: 3.25,
            spellBlock: 32.0,
            spellBlockPerLevel: 1.25,
            attackRange: 175.0,
            hpRegen: 3.0,
            hpRegenPerLevel: 1.0,
            mpRegen: 8.0,
            mpRegenPerLevel: 0.8,
            crit: 0.0,
            critPerLevel: 0.0,
            attackDamage: 60.0,
            attackDamagePerLevel: 5.0,
            attackSpeed: 0.651,
            attackSpeedPerLevel: 2.5,
        );

        self::assertSame(580.0, $stats->hp());
        self::assertSame(90.0, $stats->hpPerLevel());
        self::assertSame(350.0, $stats->mp());
        self::assertSame(32.0, $stats->mpPerLevel());
        self::assertSame(345.0, $stats->moveSpeed());
        self::assertSame(38.0, $stats->armor());
        self::assertSame(3.25, $stats->armorPerLevel());
        self::assertSame(32.0, $stats->spellBlock());
        self::assertSame(1.25, $stats->spellBlockPerLevel());
        self::assertSame(175.0, $stats->attackRange());
        self::assertSame(3.0, $stats->hpRegen());
        self::assertSame(1.0, $stats->hpRegenPerLevel());
        self::assertSame(8.0, $stats->mpRegen());
        self::assertSame(0.8, $stats->mpRegenPerLevel());
        self::assertSame(0.0, $stats->crit());
        self::assertSame(0.0, $stats->critPerLevel());
        self::assertSame(60.0, $stats->attackDamage());
        self::assertSame(5.0, $stats->attackDamagePerLevel());
        self::assertSame(0.651, $stats->attackSpeed());
        self::assertSame(2.5, $stats->attackSpeedPerLevel());
    }
}
