<?php

declare(strict_types=1);

namespace App\Tests\Unit\Summoner\Domain\ValueObject;

use App\Summoner\Domain\ValueObject\RiotId;
use PHPUnit\Framework\TestCase;

final class RiotIdTest extends TestCase
{
    public function testCanCreateWithGameNameAndTagLine(): void
    {
        $riotId = RiotId::create('PlayerName', 'EUW');

        $this->assertSame('PlayerName', $riotId->gameName());
        $this->assertSame('EUW', $riotId->tagLine());
    }

    public function testFullNameReturnsCorrectFormat(): void
    {
        $riotId = RiotId::create('Faker', 'KR1');

        $this->assertSame('Faker#KR1', $riotId->fullName());
        $this->assertSame('Faker#KR1', (string) $riotId);
    }

    public function testCanCreateFromFullName(): void
    {
        $riotId = RiotId::fromFullName('TestPlayer#NA1');

        $this->assertSame('TestPlayer', $riotId->gameName());
        $this->assertSame('NA1', $riotId->tagLine());
    }

    public function testFromFullNameThrowsExceptionForInvalidFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Riot ID format. Expected "GameName#TagLine"');

        RiotId::fromFullName('InvalidFormatWithoutHash');
    }

    public function testCreateThrowsExceptionForEmptyGameName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Game name cannot be empty');

        RiotId::create('', 'TAG');
    }

    public function testCreateThrowsExceptionForEmptyTagLine(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Tag line cannot be empty');

        RiotId::create('PlayerName', '');
    }

    public function testEqualsReturnsTrueForSameValues(): void
    {
        $riotId1 = RiotId::create('Player', 'TAG');
        $riotId2 = RiotId::create('Player', 'TAG');

        $this->assertTrue($riotId1->equals($riotId2));
    }

    public function testEqualsReturnsFalseForDifferentGameName(): void
    {
        $riotId1 = RiotId::create('Player1', 'TAG');
        $riotId2 = RiotId::create('Player2', 'TAG');

        $this->assertFalse($riotId1->equals($riotId2));
    }

    public function testEqualsReturnsFalseForDifferentTagLine(): void
    {
        $riotId1 = RiotId::create('Player', 'TAG1');
        $riotId2 = RiotId::create('Player', 'TAG2');

        $this->assertFalse($riotId1->equals($riotId2));
    }

    public function testCanCreateWithSpacesInGameName(): void
    {
        $riotId = RiotId::create('Player With Spaces', 'TAG');

        $this->assertSame('Player With Spaces', $riotId->gameName());
        $this->assertSame('Player With Spaces#TAG', $riotId->fullName());
    }

    public function testFromFullNameHandlesMultipleHashCharacters(): void
    {
        // Only the first # is used as separator
        $riotId = RiotId::fromFullName('Player#Name#TAG');

        $this->assertSame('Player', $riotId->gameName());
        $this->assertSame('Name#TAG', $riotId->tagLine());
    }
}
