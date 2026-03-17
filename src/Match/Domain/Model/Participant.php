<?php

declare(strict_types=1);

namespace App\Match\Domain\Model;

use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\SummonerPuuid;

final class Participant
{
    public function __construct(
        private readonly SummonerPuuid $summonerPuuid,
        private readonly string $puuid,
        private readonly int $championId,
        private readonly bool $win,
        private readonly KDA $kda,
        private readonly ParticipantStats $stats,
    ) {
        if ($championId <= 0) {
            throw new \InvalidArgumentException('championId must be positive');
        }
    }

    public function summonerPuuid(): SummonerPuuid
    {
        return $this->summonerPuuid;
    }

    public function championId(): int
    {
        return $this->championId;
    }

    public function win(): bool
    {
        return $this->win;
    }

    public function kda(): KDA
    {
        return $this->kda;
    }

    public function stats(): ParticipantStats
    {
        return $this->stats;
    }

    public function puuid(): string
    {
        return $this->puuid;
    }
}
