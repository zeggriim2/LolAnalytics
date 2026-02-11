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
        public array $items,
    ) {
    }

    public static function fromDomain(Participant $participant): self
    {
        $kda = $participant->kda();

        return new self(
            puuid: $participant->puuid(),
            summonerId: (string) $participant->summonerId(),
            championId: $participant->championId(),
            kills: $kda->kills(),
            deaths: $kda->deaths(),
            assists: $kda->assists(),
            kda: 0 === $kda->deaths() ? 'Perfect' : number_format(($kda->kills() + $kda->assists()) / $kda->deaths(), 2),
            win: $participant->win(),
            items: $participant->items(),
        );
    }
}
