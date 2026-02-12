<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Domain\ValueObject;

use App\Match\Domain\ValueObjet\SummonerPuuid;
use PHPUnit\Framework\TestCase;

final class SummonerIdTest extends TestCase
{
    public function testCanCreateSummonerIdFromString(): void
    {
        $summonerId = SummonerPuuid::fromString('summoner123');

        $this->assertInstanceOf(SummonerPuuid::class, $summonerId);
        $this->assertSame('summoner123', (string) $summonerId);
    }

    public function testCannotCreateEmptySummonerId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('SummonerPuuid cannot be empty');

        SummonerPuuid::fromString('');
    }

    public function testEqualsReturnsTrueForSameValue(): void
    {
        $summonerId1 = SummonerPuuid::fromString('summoner123');
        $summonerId2 = SummonerPuuid::fromString('summoner123');

        $this->assertTrue($summonerId1->equals($summonerId2));
    }

    public function testEqualsReturnsFalseForDifferentValues(): void
    {
        $summonerId1 = SummonerPuuid::fromString('summoner123');
        $summonerId2 = SummonerPuuid::fromString('summoner456');

        $this->assertFalse($summonerId1->equals($summonerId2));
    }

    public function testToStringReturnsCorrectValue(): void
    {
        $summonerId = SummonerPuuid::fromString('summoner123');

        $this->assertSame('summoner123', (string) $summonerId);
    }
}
