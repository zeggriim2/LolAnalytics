<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Domain\Model;

use App\Match\Domain\Model\Participant;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\SummonerPuuid;
use PHPUnit\Framework\TestCase;

final class ParticipantTest extends TestCase
{
    public function testCanCreateParticipant(): void
    {
        $summonerId = SummonerPuuid::fromString('summoner123');
        $kda = new KDA(10, 5, 15);
        $items = ['item1', 'item2', 'item3'];

        $participant = new Participant(
            summonerId: $summonerId,
            puuid: 'puuid-abc-123',
            championId: 157,
            win: true,
            kda: $kda,
            items: $items
        );

        $this->assertInstanceOf(Participant::class, $participant);
        $this->assertSame($summonerId, $participant->summonerId());
        $this->assertSame('puuid-abc-123', $participant->puuid());
        $this->assertSame(157, $participant->championId());
        $this->assertTrue($participant->win());
        $this->assertSame($kda, $participant->kda());
        $this->assertSame($items, $participant->items());
    }

    public function testCanCreateParticipantWithLoss(): void
    {
        $summonerId = SummonerPuuid::fromString('summoner456');
        $kda = new KDA(3, 10, 5);

        $participant = new Participant(
            summonerId: $summonerId,
            puuid: 'puuid-def-456',
            championId: 64,
            win: false,
            kda: $kda,
            items: []
        );

        $this->assertFalse($participant->win());
    }

    public function testCanCreateParticipantWithEmptyItems(): void
    {
        $summonerId = SummonerPuuid::fromString('summoner789');
        $kda = new KDA(5, 5, 5);

        $participant = new Participant(
            summonerId: $summonerId,
            puuid: 'puuid-ghi-789',
            championId: 1,
            win: true,
            kda: $kda,
            items: []
        );

        $this->assertSame([], $participant->items());
    }

    public function testCannotCreateParticipantWithZeroChampionId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('championId must be positive');

        new Participant(
            summonerId: SummonerPuuid::fromString('summoner123'),
            puuid: 'puuid-abc-123',
            championId: 0,
            win: true,
            kda: new KDA(10, 5, 15),
            items: []
        );
    }

    public function testCannotCreateParticipantWithNegativeChampionId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('championId must be positive');

        new Participant(
            summonerId: SummonerPuuid::fromString('summoner123'),
            puuid: 'puuid-abc-123',
            championId: -1,
            win: true,
            kda: new KDA(10, 5, 15),
            items: []
        );
    }

    public function testSummonerIdIsImmutable(): void
    {
        $summonerId = SummonerPuuid::fromString('summoner123');
        $participant = new Participant(
            summonerId: $summonerId,
            puuid: 'puuid-abc-123',
            championId: 157,
            win: true,
            kda: new KDA(10, 5, 15),
            items: []
        );

        $retrievedSummonerId = $participant->summonerId();
        $this->assertTrue($summonerId->equals($retrievedSummonerId));
    }

    public function testKdaIsImmutable(): void
    {
        $kda = new KDA(10, 5, 15);
        $participant = new Participant(
            summonerId: SummonerPuuid::fromString('summoner123'),
            puuid: 'puuid-abc-123',
            championId: 157,
            win: true,
            kda: $kda,
            items: []
        );

        $retrievedKda = $participant->kda();
        $this->assertSame($kda, $retrievedKda);
        $this->assertSame(10, $retrievedKda->kills());
        $this->assertSame(5, $retrievedKda->deaths());
        $this->assertSame(15, $retrievedKda->assists());
    }
}
