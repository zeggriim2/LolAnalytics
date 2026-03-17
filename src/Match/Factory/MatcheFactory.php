<?php

declare(strict_types=1);

namespace App\Match\Factory;

use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\Model\ParticipantStats;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerPuuid;
use App\SharedContext\Domain\ValueObjet\Platform;

final class MatcheFactory
{
    /**
     * Crée un agrégat Match à partir du payload API Riot (tableau associatif simplifié)
     * Expected shape (example):
     * [
     * 'metadata' => ['matchId' => '...'],
     * 'info' => [ 'gameCreation' => 1234567890, 'gameDuration' => 1800, 'participants' => [ ... ]]
     * ].
     *
     * @param array<mixed> $payload
     */
    public static function fromRiotPayload(array $payload): Matche
    {
        $matchId = MatchId::fromString($payload['metadata']['matchId']);
        $platform = Platform::tryFrom(strtolower(explode('_', $payload['metadata']['matchId'])[0]));

        if (null === $platform) {
            throw new \InvalidArgumentException('Invalid platform');
        }

        $gameId = GameId::fromInt($payload['info']['gameId']);
        $gameCreationMs = $payload['info']['gameCreation'] ?? null;
        $gameDuration = (int) ($payload['info']['gameDuration'] ?? 0);
        $gameMode = $payload['info']['gameMode'] ?? null;
        $gameType = $payload['info']['gameType'] ?? null;
        $gameVersion = $payload['info']['gameVersion'] ?? null;
        $queueId = (int) ($payload['info']['queueId'] ?? null);
        $mapId = (int) ($payload['info']['mapId'] ?? null);

        if (null === $gameCreationMs) {
            throw new \InvalidArgumentException('Invalid gameCreation');
        }
        $gameCreation = (int) ($gameCreationMs / 1000);
        $playedAt = (new \DateTimeImmutable())->setTimestamp($gameCreation);

        $participants = [];

        foreach ($payload['info']['participants'] as $p) {
            $summonerPuuid = SummonerPuuid::fromString((string) ($p['puuid'] ?? $p['summonerId'] ?? ''));
            $kda = new KDA((int) ($p['kills'] ?? 0), (int) ($p['deaths'] ?? 0), (int) ($p['assists'] ?? 0));

            $items = [];

            for ($i = 0; $i <= 6; ++$i) {
                $key = 'item' . $i;

                if (isset($p[$key]) && 0 !== $p[$key]) {
                    $items[] = (string) $p[$key];
                }
            }

            $stats = new ParticipantStats(
                cs: (int) ($p['totalMinionsKilled'] ?? 0) + (int) ($p['neutralMinionsKilled'] ?? 0),
                goldEarned: (int) ($p['goldEarned'] ?? 0),
                totalDamageDealtToChampions: (int) ($p['totalDamageDealtToChampions'] ?? 0),
                totalDamageTaken: (int) ($p['totalDamageTaken'] ?? 0),
                visionScore: (int) ($p['visionScore'] ?? 0),
                lane: (string) ($p['lane'] ?? ''),
                individualPosition: (string) ($p['individualPosition'] ?? ''),
                summoner1Id: (int) ($p['summoner1Id'] ?? 0),
                summoner2Id: (int) ($p['summoner2Id'] ?? 0),
                champLevel: (int) ($p['champLevel'] ?? 1),
                wardsPlaced: (int) ($p['wardsPlaced'] ?? 0),
                wardsKilled: (int) ($p['wardsKilled'] ?? 0),
                firstBloodKill: (bool) ($p['firstBloodKill'] ?? false),
                items: $items,
            );

            $participant = new Participant(
                summonerPuuid: $summonerPuuid,
                puuid: $p['summonerId'],
                championId: (int) ($p['championId'] ?? $p['champion']),
                win: (bool) ($p['win'] ?? false),
                kda: $kda,
                stats: $stats,
            );
            $participants[] = $participant;
        }

        return Matche::create($matchId, $gameId, $playedAt, $gameDuration, $gameMode, $gameType, $mapId, $gameVersion, $queueId, $platform, $participants);
    }
}
