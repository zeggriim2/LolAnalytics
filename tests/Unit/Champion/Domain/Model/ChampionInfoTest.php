<?php

declare(strict_types=1);

namespace App\Tests\Unit\Champion\Domain\Model;

use App\Champion\Domain\Model\ChampionInfo;
use PHPUnit\Framework\TestCase;

final class ChampionInfoTest extends TestCase
{
    public function testCanCreateChampionInfo(): void
    {
        $info = new ChampionInfo(
            attack: 8,
            defense: 4,
            magic: 3,
            difficulty: 7,
        );

        self::assertSame(8, $info->attack());
        self::assertSame(4, $info->defense());
        self::assertSame(3, $info->magic());
        self::assertSame(7, $info->difficulty());
    }

    public function testCanCreateWithBoundaryValues(): void
    {
        $info = new ChampionInfo(attack: 0, defense: 0, magic: 0, difficulty: 0);

        self::assertSame(0, $info->attack());
        self::assertSame(0, $info->defense());
        self::assertSame(0, $info->magic());
        self::assertSame(0, $info->difficulty());

        $infoMax = new ChampionInfo(attack: 10, defense: 10, magic: 10, difficulty: 10);

        self::assertSame(10, $infoMax->attack());
        self::assertSame(10, $infoMax->defense());
        self::assertSame(10, $infoMax->magic());
        self::assertSame(10, $infoMax->difficulty());
    }

    public function testThrowsExceptionWhenAttackIsNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('attack must be between 0 and 10');

        new ChampionInfo(attack: -1, defense: 5, magic: 5, difficulty: 5);
    }

    public function testThrowsExceptionWhenAttackExceedsTen(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('attack must be between 0 and 10');

        new ChampionInfo(attack: 11, defense: 5, magic: 5, difficulty: 5);
    }

    public function testThrowsExceptionWhenDefenseIsOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('defense must be between 0 and 10');

        new ChampionInfo(attack: 5, defense: 15, magic: 5, difficulty: 5);
    }

    public function testThrowsExceptionWhenMagicIsOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('magic must be between 0 and 10');

        new ChampionInfo(attack: 5, defense: 5, magic: -3, difficulty: 5);
    }

    public function testThrowsExceptionWhenDifficultyIsOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('difficulty must be between 0 and 10');

        new ChampionInfo(attack: 5, defense: 5, magic: 5, difficulty: 11);
    }
}
