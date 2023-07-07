<?php

declare(strict_types=1);

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class ObjectiveDto
{
    private bool $first;
    private int $kills;

    public function isFirst(): bool
    {
        return $this->first;
    }

    public function setFirst(bool $first): self
    {
        $this->first = $first;

        return $this;
    }

    public function getKills(): int
    {
        return $this->kills;
    }

    public function setKills(int $kills): self
    {
        $this->kills = $kills;

        return $this;
    }
}
