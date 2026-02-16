<?php

declare(strict_types=1);

namespace App\Tests\Unit\Champion\Domain\Model;

use App\Champion\Domain\Model\ChampionImage;
use PHPUnit\Framework\TestCase;

final class ChampionImageTest extends TestCase
{
    public function testCanCreateChampionImage(): void
    {
        $image = new ChampionImage(
            full: 'Aatrox.png',
            sprite: 'champion0.png',
            group: 'champion',
            x: 0,
            y: 0,
            w: 48,
            h: 48,
        );

        self::assertSame('Aatrox.png', $image->full());
        self::assertSame('champion0.png', $image->sprite());
        self::assertSame('champion', $image->group());
        self::assertSame(0, $image->x());
        self::assertSame(0, $image->y());
        self::assertSame(48, $image->w());
        self::assertSame(48, $image->h());
    }

    public function testCanCreateWithDifferentCoordinates(): void
    {
        $image = new ChampionImage(
            full: 'Yasuo.png',
            sprite: 'champion4.png',
            group: 'champion',
            x: 96,
            y: 48,
            w: 48,
            h: 48,
        );

        self::assertSame(96, $image->x());
        self::assertSame(48, $image->y());
    }
}
