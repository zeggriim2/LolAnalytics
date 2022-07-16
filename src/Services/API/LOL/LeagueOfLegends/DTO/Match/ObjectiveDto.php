<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class ObjectiveDto
{
    private bool $first;
    private int $kills;

    /**
     * @return bool
     */
    public function isFirst(): bool
    {
        return $this->first;
    }

    /**
     * @param bool $first
     * @return ObjectiveDto
     */
    public function setFirst(bool $first): ObjectiveDto
    {
        $this->first = $first;
        return $this;
    }

    /**
     * @return int
     */
    public function getKills(): int
    {
        return $this->kills;
    }

    /**
     * @param int $kills
     * @return ObjectiveDto
     */
    public function setKills(int $kills): ObjectiveDto
    {
        $this->kills = $kills;
        return $this;
    }
}