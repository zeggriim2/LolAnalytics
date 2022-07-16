<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class PerkStatsDto
{
    private int $defense;
    private int $flex;
    private int $offense;

    /**
     * @return int
     */
    public function getDefense(): int
    {
        return $this->defense;
    }

    /**
     * @param int $defense
     * @return PerkStatsDto
     */
    public function setDefense(int $defense): PerkStatsDto
    {
        $this->defense = $defense;
        return $this;
    }

    /**
     * @return int
     */
    public function getFlex(): int
    {
        return $this->flex;
    }

    /**
     * @param int $flex
     * @return PerkStatsDto
     */
    public function setFlex(int $flex): PerkStatsDto
    {
        $this->flex = $flex;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffense(): int
    {
        return $this->offense;
    }

    /**
     * @param int $offense
     * @return PerkStatsDto
     */
    public function setOffense(int $offense): PerkStatsDto
    {
        $this->offense = $offense;
        return $this;
    }
}