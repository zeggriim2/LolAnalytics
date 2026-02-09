<?php

declare(strict_types=1);

namespace App\Tests\Unit\Summoner\Domain\ValueObject;

use App\Summoner\Domain\ValueObject\Puuid;
use PHPUnit\Framework\TestCase;

final class PuuidTest extends TestCase
{
    public function testCanCreateFromString(): void
    {
        $puuid = Puuid::fromString('valid-puuid-123');

        $this->assertSame('valid-puuid-123', $puuid->value());
        $this->assertSame('valid-puuid-123', (string) $puuid);
    }

    public function testThrowsExceptionForEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Puuid cannot be empty');

        Puuid::fromString('');
    }

    public function testEqualsReturnsTrueForSameValue(): void
    {
        $puuid1 = Puuid::fromString('same-puuid');
        $puuid2 = Puuid::fromString('same-puuid');

        $this->assertTrue($puuid1->equals($puuid2));
    }

    public function testEqualsReturnsFalseForDifferentValue(): void
    {
        $puuid1 = Puuid::fromString('puuid-one');
        $puuid2 = Puuid::fromString('puuid-two');

        $this->assertFalse($puuid1->equals($puuid2));
    }

    public function testCanCreateWithLongPuuid(): void
    {
        $longPuuid = str_repeat('a', 78);
        $puuid = Puuid::fromString($longPuuid);

        $this->assertSame($longPuuid, $puuid->value());
    }

    public function testCanCreateWithSpecialCharacters(): void
    {
        $specialPuuid = 'puuid-with-dashes_and_underscores';
        $puuid = Puuid::fromString($specialPuuid);

        $this->assertSame($specialPuuid, $puuid->value());
    }
}
