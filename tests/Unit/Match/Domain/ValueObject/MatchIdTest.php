<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Domain\ValueObject;

use App\Match\Domain\ValueObjet\MatchId;
use PHPUnit\Framework\TestCase;

final class MatchIdTest extends TestCase
{
    public function testCanCreateMatchIdFromString(): void
    {
        $matchId = MatchId::fromString('EUW1_1234567890');

        $this->assertInstanceOf(MatchId::class, $matchId);
        $this->assertSame('EUW1_1234567890', (string) $matchId);
    }

    public function testCannotCreateEmptyMatchId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('MatchId cannot be empty');

        MatchId::fromString('');
    }

    public function testEqualsReturnsTrueForSameValue(): void
    {
        $matchId1 = MatchId::fromString('EUW1_1234567890');
        $matchId2 = MatchId::fromString('EUW1_1234567890');

        $this->assertTrue($matchId1->equals($matchId2));
    }

    public function testEqualsReturnsFalseForDifferentValues(): void
    {
        $matchId1 = MatchId::fromString('EUW1_1234567890');
        $matchId2 = MatchId::fromString('EUW1_9876543210');

        $this->assertFalse($matchId1->equals($matchId2));
    }

    public function testToStringReturnsCorrectValue(): void
    {
        $matchId = MatchId::fromString('EUW1_1234567890');

        $this->assertSame('EUW1_1234567890', (string) $matchId);
    }
}
