<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Domain\Model;

use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerPuuid;
use App\SharedContext\Domain\ValueObjet\Platform;
use PHPUnit\Framework\TestCase;

final class MatcheTest extends TestCase
{
    private function createParticipant(string $summonerIdValue, int $championId = 157): Participant
    {
        return new Participant(
            summonerId: SummonerPuuid::fromString($summonerIdValue),
            puuid: 'puuid-' . $summonerIdValue,
            championId: $championId,
            win: true,
            kda: new KDA(10, 5, 15),
            items: ['item1', 'item2']
        );
    }

    public function testCanCreateMatche(): void
    {
        $matchId = MatchId::fromString('EUW1_1234567890');
        $gameId = GameId::fromInt(1234567890);
        $playedAt = new \DateTimeImmutable('2024-01-15 14:30:00');
        $participants = [
            $this->createParticipant('summoner1'),
            $this->createParticipant('summoner2'),
        ];

        $match = new Matche(
            id: $matchId,
            gameId: $gameId,
            playedAt: $playedAt,
            durationSeconds: 1800,
            gameMode: 'gameMode',
            gameType: 'gameType',
            queueId: 1,
            mapId: 900,
            platform: Platform::EUW1,
            participants: $participants
        );

        $this->assertInstanceOf(Matche::class, $match);
        $this->assertSame($matchId, $match->id());
        $this->assertSame($gameId, $match->gameId());
        $this->assertSame($playedAt, $match->playedAt());
        $this->assertSame(1800, $match->durationSeconds());
        $this->assertSame($participants, $match->participants());
    }

    public function testCanCreateMatcheUsingStaticFactory(): void
    {
        $matchId = MatchId::fromString('EUW1_1234567890');
        $gameId = GameId::fromInt(1234567890);
        $playedAt = new \DateTimeImmutable('2024-01-15 14:30:00');
        $participants = [$this->createParticipant('summoner1')];

        $match = Matche::create(
            id: $matchId,
            gameId: $gameId,
            playedAt: $playedAt,
            durationSeconds: 1800,
            gameMode: 'gameMode',
            gameType: 'gameType',
            mapId: 900,
            queueId: 1,
            platform: Platform::EUW1,
            participants: $participants
        );

        $this->assertInstanceOf(Matche::class, $match);
        $this->assertSame($matchId, $match->id());
    }

    public function testCannotCreateMatcheWithZeroDuration(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('durationSeconds must be > 0');

        new Matche(
            id: MatchId::fromString('EUW1_1234567890'),
            gameId: GameId::fromInt(1234567890),
            playedAt: new \DateTimeImmutable(),
            durationSeconds: 0,
            gameMode: 'gameMode',
            gameType: 'gameType',
            queueId: 1,
            mapId: 900,
            platform: Platform::EUW1,
            participants: [$this->createParticipant('summoner1')]
        );
    }

    public function testCannotCreateMatcheWithNegativeDuration(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('durationSeconds must be > 0');

        new Matche(
            id: MatchId::fromString('EUW1_1234567890'),
            gameId: GameId::fromInt(1234567890),
            playedAt: new \DateTimeImmutable(),
            durationSeconds: -100,
            gameMode: 'gameMode',
            gameType: 'gameType',
            queueId: 1,
            mapId: 900,
            platform: Platform::EUW1,
            participants: [$this->createParticipant('summoner1')]
        );
    }

    public function testCannotCreateMatcheWithoutParticipants(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Match must have at least one participant');

        new Matche(
            id: MatchId::fromString('EUW1_1234567890'),
            gameId: GameId::fromInt(1234567890),
            playedAt: new \DateTimeImmutable(),
            durationSeconds: 1800,
            gameMode: 'gameMode',
            gameType: 'gameType',
            queueId: 1,
            mapId: 900,
            platform: Platform::EUW1,
            participants: []
        );
    }

    public function testCannotCreateMatcheWithDuplicateParticipants(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Duplicate participant summonerId');

        $participants = [
            $this->createParticipant('summoner1'),
            $this->createParticipant('summoner1'), // Duplicate
        ];

        new Matche(
            id: MatchId::fromString('EUW1_1234567890'),
            gameId: GameId::fromInt(1234567890),
            playedAt: new \DateTimeImmutable(),
            durationSeconds: 1800,
            gameMode: 'gameMode',
            gameType: 'gameType',
            queueId: 1,
            mapId: 900,
            platform: Platform::EUW1,
            participants: $participants
        );
    }

    public function testCanCreateMatcheWithMultipleUniqueParticipants(): void
    {
        $participants = [
            $this->createParticipant('summoner1', 1),
            $this->createParticipant('summoner2', 2),
            $this->createParticipant('summoner3', 3),
            $this->createParticipant('summoner4', 4),
            $this->createParticipant('summoner5', 5),
            $this->createParticipant('summoner6', 6),
            $this->createParticipant('summoner7', 7),
            $this->createParticipant('summoner8', 8),
            $this->createParticipant('summoner9', 9),
            $this->createParticipant('summoner10', 10),
        ];

        $match = new Matche(
            id: MatchId::fromString('EUW1_1234567890'),
            gameId: GameId::fromInt(1234567890),
            playedAt: new \DateTimeImmutable(),
            durationSeconds: 1800,
            gameMode: 'gameMode',
            gameType: 'gameType',
            queueId: 1,
            mapId: 900,
            platform: Platform::EUW1,
            participants: $participants
        );

        $this->assertCount(10, $match->participants());
    }

    public function testMatchPropertiesAreImmutable(): void
    {
        $matchId = MatchId::fromString('EUW1_1234567890');
        $gameId = GameId::fromInt(1234567890);
        $playedAt = new \DateTimeImmutable('2024-01-15 14:30:00');
        $participants = [$this->createParticipant('summoner1')];

        $match = new Matche(
            id: $matchId,
            gameId: $gameId,
            playedAt: $playedAt,
            durationSeconds: 1800,
            gameMode: 'gameMode',
            gameType: 'gameType',
            queueId: 1,
            mapId: 900,
            platform: Platform::EUW1,
            participants: $participants
        );

        $this->assertTrue($matchId->equals($match->id()));
        $this->assertTrue($gameId->equals($match->gameId()));
        $this->assertSame($playedAt, $match->playedAt());
        $this->assertSame($participants, $match->participants());
    }
}
