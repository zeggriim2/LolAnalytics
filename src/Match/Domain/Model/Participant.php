<?php

declare(strict_types=1);

namespace App\Match\Domain\Model;

use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\SummonerId;

final class Participant
{
    public function __construct(
        private readonly SummonerId $summonerId,
        private readonly int $championId,
        private readonly bool $win,
        private readonly KDA $kda,
        private readonly array $items
    ) {
        if ($championId <= 0) {
            throw new \InvalidArgumentException('championId must be positive');
        }
    }

    public function summonerId(): SummonerId { return $this->summonerId; }
    public function championId(): int { return $this->championId; }
    public function win(): bool { return $this->win; }
    public function kda(): KDA { return $this->kda; }
    public function items(): array { return $this->items; }
}
