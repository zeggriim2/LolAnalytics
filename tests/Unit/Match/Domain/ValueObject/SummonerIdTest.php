<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Domain\ValueObject;

use App\Match\Domain\ValueObjet\SummonerId;
use PHPUnit\Framework\TestCase;

final class SummonerIdTest extends TestCase
{
    public function testCanCreateSummonerIdFromString(): void
    {
        $summonerId = SummonerId::fromString('summoner123');

        $this->assertInstanceOf(SummonerId::class, $summonerId);
        $this->assertSame('summoner123', (string) $summonerId);
    }

    public function testCannotCreateEmptySummonerId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('SummonerId cannot be empty');

        SummonerId::fromString('');
    }

    public function testEqualsReturnsTrueForSameValue(): void
    {
        $summonerId1 = SummonerId::fromString('summoner123');
        $summonerId2 = SummonerId::fromString('summoner123');

        $this->assertTrue($summonerId1->equals($summonerId2));
    }

    public function testEqualsReturnsFalseForDifferentValues(): void
    {
        $summonerId1 = SummonerId::fromString('summoner123');
        $summonerId2 = SummonerId::fromString('summoner456');

        $this->assertFalse($summonerId1->equals($summonerId2));
    }

    public function testToStringReturnsCorrectValue(): void
    {
        $summonerId = SummonerId::fromString('summoner123');

        $this->assertSame('summoner123', (string) $summonerId);
    }
}
