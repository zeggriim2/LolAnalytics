<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Domain\ValueObject;

use App\Match\Domain\ValueObjet\GameId;
use PHPUnit\Framework\TestCase;

final class GameIdTest extends TestCase
{
    public function testCanCreateGameIdFromInt(): void
    {
        $gameId = GameId::fromInt(1234567890);

        $this->assertInstanceOf(GameId::class, $gameId);
        $this->assertSame(1234567890, $gameId->value());
    }

    public function testCannotCreateGameIdWithZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('GameId cannot be empty');

        GameId::fromInt(0);
    }

    public function testEqualsReturnsTrueForSameValue(): void
    {
        $gameId1 = GameId::fromInt(1234567890);
        $gameId2 = GameId::fromInt(1234567890);

        $this->assertTrue($gameId1->equals($gameId2));
    }

    public function testEqualsReturnsFalseForDifferentValues(): void
    {
        $gameId1 = GameId::fromInt(1234567890);
        $gameId2 = GameId::fromInt(9876543210);

        $this->assertFalse($gameId1->equals($gameId2));
    }

    public function testValueReturnsCorrectInt(): void
    {
        $gameId = GameId::fromInt(1234567890);

        $this->assertSame(1234567890, $gameId->value());
    }
}
