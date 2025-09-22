<?php

declare(strict_types=1);

namespace App\Match\Factory;

use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerId;

final class MatcheFactory
{
    /**
     * Crée un agrégat Match à partir du payload API Riot (tableau associatif simplifié)
     * Expected shape (example):
     * [
     * 'metadata' => ['matchId' => '...'],
     * 'info' => [ 'gameCreation' => 1234567890, 'gameDuration' => 1800, 'participants' => [ ... ]]
     * ]
     */
    public static function fromRiotPayload(array $payload): Matche
    {
        $matchId = MatchId::fromString($payload['metadata']['matchId']) ?? throw new \InvalidArgumentException('Invalid matchId');

        $gameCreationMs = $payload['info']['gameCreation'] ?? null;
        $gameDuration = (int)($payload['info']['gameDuration'] ?? 0);

        if(null === $gameCreationMs) {
            throw new \InvalidArgumentException('Invalid gameCreation');
        }
        $gameCreation = (int) ($gameCreationMs / 1000);
        $playedAt = (new \DateTimeImmutable())->setTimestamp($gameCreation);

        $participants = [];
        foreach ($payload['info']['participants'] as $p) {
            $summonerId = SummonerId::fromString((string)($p['puuid'] ?? $p['summonerId'] ?? ''));
            $kda = new KDA((int)($p['kills'] ?? 0), (int)($p['deaths'] ?? 0), (int)($p['assists'] ?? 0));

            $items = [];
            for ($i = 0; $i <= 6; $i++) {
                $key = 'item' . $i;
                if (isset($p[$key]) && $p[$key] !== 0) $items[] = (int)$p[$key];
            }
            $participant = new Participant(
                $summonerId,
                (int)($p['championId'] ?? $p['champion']) ,
                (bool)($p['win'] ?? false),
                $kda,
                $items
            );
            $participants[] = $participant;
        }

        return Matche::create($matchId, $playedAt, $gameDuration, $participants);
    }
}
