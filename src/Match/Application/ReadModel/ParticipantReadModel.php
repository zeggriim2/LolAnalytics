<?php

declare(strict_types=1);

namespace App\Match\Application\ReadModel;

use App\Match\Domain\Model\Participant;

final readonly class ParticipantReadModel
{
    /**
     * @param string[] $items
     */
    public function __construct(
        public string $puuid,
        public string $summonerId,
        public int $championId,
        public int $kills,
        public int $deaths,
        public int $assists,
        public string $kda,
        public bool $win,
        public ?string $gameName,
        public int $cs,
        public int $goldEarned,
        public int $totalDamageDealtToChampions,
        public int $totalDamageTaken,
        public int $visionScore,
        public string $lane,
        public string $individualPosition,
        public int $summoner1Id,
        public int $summoner2Id,
        public int $champLevel,
        public int $wardsPlaced,
        public int $wardsKilled,
        public bool $firstBloodKill,
        public array $items,
    ) {
    }

    public static function fromDomain(Participant $participant, ?string $gameName = null): self
    {
        $kda = $participant->kda();
        $stats = $participant->stats();

        return new self(
            puuid: $participant->puuid(),
            summonerId: (string) $participant->summonerPuuid(),
            championId: $participant->championId(),
            kills: $kda->kills(),
            deaths: $kda->deaths(),
            assists: $kda->assists(),
            kda: 0 === $kda->deaths() ? 'Perfect' : number_format(($kda->kills() + $kda->assists()) / $kda->deaths(), 2),
            win: $participant->win(),
            gameName: $gameName,
            cs: $stats->cs(),
            goldEarned: $stats->goldEarned(),
            totalDamageDealtToChampions: $stats->totalDamageDealtToChampions(),
            totalDamageTaken: $stats->totalDamageTaken(),
            visionScore: $stats->visionScore(),
            lane: $stats->lane(),
            individualPosition: $stats->individualPosition(),
            summoner1Id: $stats->summoner1Id(),
            summoner2Id: $stats->summoner2Id(),
            champLevel: $stats->champLevel(),
            wardsPlaced: $stats->wardsPlaced(),
            wardsKilled: $stats->wardsKilled(),
            firstBloodKill: $stats->firstBloodKill(),
            items: $stats->items(),
        );
    }
}
