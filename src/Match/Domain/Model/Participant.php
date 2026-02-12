<?php

declare(strict_types=1);

namespace App\Match\Domain\Model;

use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\SummonerPuuid;

final class Participant
{
    /**
     * @param string[] $items
     */
    public function __construct(
        private readonly SummonerPuuid $summonerPuuid,
        private readonly string $puuid,
        private readonly int $championId,
        private readonly bool $win,
        private readonly KDA $kda,
        private readonly array $items
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

    /**
     * @return string[]
     */
    public function items(): array
    {
        return $this->items;
    }

    public function puuid(): string
    {
        return $this->puuid;
    }
}
