<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Domain\Model;

use App\Match\Domain\Model\Participant;
use App\Match\Domain\Model\ParticipantStats;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\SummonerPuuid;
use PHPUnit\Framework\TestCase;

final class ParticipantTest extends TestCase
{
    private function makeStats(array $items = []): ParticipantStats
    {
        return new ParticipantStats(
            cs: 150,
            goldEarned: 12000,
            totalDamageDealtToChampions: 45000,
            totalDamageTaken: 30000,
            visionScore: 25,
            lane: 'MIDDLE',
            individualPosition: 'MIDDLE',
            summoner1Id: 4,
            summoner2Id: 14,
            champLevel: 16,
            wardsPlaced: 10,
            wardsKilled: 5,
            firstBloodKill: false,
            items: $items,
        );
    }

    public function testCanCreateParticipant(): void
    {
        $summonerPuuid = SummonerPuuid::fromString('summoner123');
        $kda = new KDA(10, 5, 15);
        $items = ['item1', 'item2', 'item3'];
        $stats = $this->makeStats($items);

        $participant = new Participant(
            summonerPuuid: $summonerPuuid,
            puuid: 'puuid-abc-123',
            championId: 157,
            win: true,
            kda: $kda,
            stats: $stats,
        );

        $this->assertInstanceOf(Participant::class, $participant);
        $this->assertSame($summonerPuuid, $participant->summonerPuuid());
        $this->assertSame('puuid-abc-123', $participant->puuid());
        $this->assertSame(157, $participant->championId());
        $this->assertTrue($participant->win());
        $this->assertSame($kda, $participant->kda());
        $this->assertSame($stats, $participant->stats());
        $this->assertSame($items, $participant->stats()->items());
    }

    public function testCanCreateParticipantWithLoss(): void
    {
        $summonerPuuid = SummonerPuuid::fromString('summoner456');
        $kda = new KDA(3, 10, 5);

        $participant = new Participant(
            summonerPuuid: $summonerPuuid,
            puuid: 'puuid-def-456',
            championId: 64,
            win: false,
            kda: $kda,
            stats: $this->makeStats(),
        );

        $this->assertFalse($participant->win());
    }

    public function testCanCreateParticipantWithEmptyItems(): void
    {
        $summonerPuuid = SummonerPuuid::fromString('summoner789');
        $kda = new KDA(5, 5, 5);

        $participant = new Participant(
            summonerPuuid: $summonerPuuid,
            puuid: 'puuid-ghi-789',
            championId: 1,
            win: true,
            kda: $kda,
            stats: $this->makeStats([]),
        );

        $this->assertSame([], $participant->stats()->items());
    }

    public function testCannotCreateParticipantWithZeroChampionId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('championId must be positive');

        new Participant(
            summonerPuuid: SummonerPuuid::fromString('summoner123'),
            puuid: 'puuid-abc-123',
            championId: 0,
            win: true,
            kda: new KDA(10, 5, 15),
            stats: $this->makeStats(),
        );
    }

    public function testCannotCreateParticipantWithNegativeChampionId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('championId must be positive');

        new Participant(
            summonerPuuid: SummonerPuuid::fromString('summoner123'),
            puuid: 'puuid-abc-123',
            championId: -1,
            win: true,
            kda: new KDA(10, 5, 15),
            stats: $this->makeStats(),
        );
    }

    public function testSummonerPuuidIsImmutable(): void
    {
        $summonerPuuid = SummonerPuuid::fromString('summoner123');
        $participant = new Participant(
            summonerPuuid: $summonerPuuid,
            puuid: 'puuid-abc-123',
            championId: 157,
            win: true,
            kda: new KDA(10, 5, 15),
            stats: $this->makeStats(),
        );

        $retrievedSummonerPuuid = $participant->summonerPuuid();
        $this->assertTrue($summonerPuuid->equals($retrievedSummonerPuuid));
    }

    public function testKdaIsImmutable(): void
    {
        $kda = new KDA(10, 5, 15);
        $participant = new Participant(
            summonerPuuid: SummonerPuuid::fromString('summoner123'),
            puuid: 'puuid-abc-123',
            championId: 157,
            win: true,
            kda: $kda,
            stats: $this->makeStats(),
        );

        $retrievedKda = $participant->kda();
        $this->assertSame($kda, $retrievedKda);
        $this->assertSame(10, $retrievedKda->kills());
        $this->assertSame(5, $retrievedKda->deaths());
        $this->assertSame(15, $retrievedKda->assists());
    }
}
