<?php

declare(strict_types=1);

namespace App\Tests\Unit\League\Application\Dto;

use App\League\Application\Dto\LeagueEntryListDto;
use App\League\Domain\Model\LeagueEntry;
use PHPUnit\Framework\TestCase;

final class LeagueEntryListDtoTest extends TestCase
{
    public function testWinRateIsComputedCorrectly(): void
    {
        $entry = LeagueEntry::create('puuid', 1000, 60, 40, null, false, false, false);

        $dto = LeagueEntryListDto::fromDomain($entry, 1);

        $this->assertSame(60, $dto->winRate);
    }

    public function testWinRateIsRounded(): void
    {
        // 2/3 = 66.666... → 67%
        $entry = LeagueEntry::create('puuid', 500, 2, 1, null, false, false, false);

        $dto = LeagueEntryListDto::fromDomain($entry, 1);

        $this->assertSame(67, $dto->winRate);
    }

    public function testWinRateIsZeroWhenNoGames(): void
    {
        $entry = LeagueEntry::create('puuid', 0, 0, 0, null, false, false, false);

        $dto = LeagueEntryListDto::fromDomain($entry, 1);

        $this->assertSame(0, $dto->winRate);
    }

    public function testRankIsAssignedCorrectly(): void
    {
        $entry = LeagueEntry::create('puuid', 100, 10, 5, null, false, false, false);

        $dto = LeagueEntryListDto::fromDomain($entry, 42);

        $this->assertSame(42, $dto->rank);
    }

    public function testAllFieldsMappedFromDomain(): void
    {
        $entry = LeagueEntry::create('my-puuid', 1500, 100, 50, null, true, true, false);

        $dto = LeagueEntryListDto::fromDomain($entry, 3);

        $this->assertSame('my-puuid', $dto->puuid);
        $this->assertSame(1500, $dto->leaguePoints);
        $this->assertSame(100, $dto->wins);
        $this->assertSame(50, $dto->losses);
        $this->assertTrue($dto->hotStreak);
        $this->assertTrue($dto->veteran);
        $this->assertFalse($dto->freshBlood);
    }
}
