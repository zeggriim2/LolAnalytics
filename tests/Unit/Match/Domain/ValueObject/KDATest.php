<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Domain\ValueObject;

use App\Match\Domain\ValueObjet\KDA;
use PHPUnit\Framework\TestCase;

final class KDATest extends TestCase
{
    public function testCanCreateKDA(): void
    {
        $kda = new KDA(10, 5, 15);

        $this->assertInstanceOf(KDA::class, $kda);
        $this->assertSame(10, $kda->kills());
        $this->assertSame(5, $kda->deaths());
        $this->assertSame(15, $kda->assists());
    }

    public function testCanCreateKDAWithZeroValues(): void
    {
        $kda = new KDA(0, 0, 0);

        $this->assertSame(0, $kda->kills());
        $this->assertSame(0, $kda->deaths());
        $this->assertSame(0, $kda->assists());
    }

    public function testCannotCreateKDAWithNegativeKills(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('KDA parts must be >= 0');

        new KDA(-1, 5, 15);
    }

    public function testCannotCreateKDAWithNegativeDeaths(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('KDA parts must be >= 0');

        new KDA(10, -1, 15);
    }

    public function testCannotCreateKDAWithNegativeAssists(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('KDA parts must be >= 0');

        new KDA(10, 5, -1);
    }

    public function testRatioCalculationWithDeaths(): void
    {
        $kda = new KDA(10, 5, 15);

        // (10 + 15) / 5 = 5.0
        $this->assertSame(5.0, $kda->ratio());
    }

    public function testRatioCalculationWithoutDeaths(): void
    {
        $kda = new KDA(10, 0, 15);

        // Perfect KDA: (10 + 15) = 25
        $this->assertSame(25.0, $kda->ratio());
    }

    public function testRatioCalculationWithZeroKillsAndAssists(): void
    {
        $kda = new KDA(0, 5, 0);

        // (0 + 0) / 5 = 0.0
        $this->assertSame(0.0, $kda->ratio());
    }

    public function testRatioCalculationWithAllZeros(): void
    {
        $kda = new KDA(0, 0, 0);

        // No deaths = perfect KDA: 0 + 0 = 0
        $this->assertSame(0.0, $kda->ratio());
    }

    public function testRatioCalculationReturnsFloat(): void
    {
        $kda = new KDA(7, 3, 8);

        // (7 + 8) / 3 = 5.0
        $this->assertIsFloat($kda->ratio());
        $this->assertSame(5.0, $kda->ratio());
    }
}
