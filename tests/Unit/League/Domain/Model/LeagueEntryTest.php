<?php

declare(strict_types=1);

namespace App\Tests\Unit\League\Domain\Model;

use App\League\Domain\Model\LeagueEntry;
use PHPUnit\Framework\TestCase;

final class LeagueEntryTest extends TestCase
{
    public function testCreateWithAllFields(): void
    {
        $entry = LeagueEntry::create(
            puuid: 'test-puuid-123',
            leaguePoints: 1500,
            wins: 120,
            losses: 80,
            rank: null,
            hotStreak: true,
            veteran: false,
            freshBlood: false,
        );

        $this->assertSame('test-puuid-123', $entry->puuid());
        $this->assertSame(1500, $entry->leaguePoints());
        $this->assertSame(120, $entry->wins());
        $this->assertSame(80, $entry->losses());
        $this->assertNull($entry->rank());
        $this->assertTrue($entry->hotStreak());
        $this->assertFalse($entry->veteran());
        $this->assertFalse($entry->freshBlood());
    }

    public function testCreateWithRank(): void
    {
        $entry = LeagueEntry::create('puuid', 200, 10, 5, 'I', false, false, false);

        $this->assertSame('I', $entry->rank());
    }

    public function testVeteranFlag(): void
    {
        $entry = LeagueEntry::create('puuid', 300, 200, 100, null, false, true, false);

        $this->assertTrue($entry->veteran());
    }

    public function testFreshBloodFlag(): void
    {
        $entry = LeagueEntry::create('puuid', 100, 5, 3, null, false, false, true);

        $this->assertTrue($entry->freshBlood());
    }
}
