<?php

declare(strict_types=1);

namespace App\Match\Factory;

use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
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

            $participant = new Participant(
                $summonerPuuid,
                $p['summonerId'],
                (int) ($p['championId'] ?? $p['champion']),
                (bool) ($p['win'] ?? false),
                $kda,
                $items
            );
            $participants[] = $participant;
        }

        return Matche::create($matchId, $gameId, $playedAt, $gameDuration, $gameMode, $gameType, $mapId, $queueId, $platform, $participants);
    }
}
