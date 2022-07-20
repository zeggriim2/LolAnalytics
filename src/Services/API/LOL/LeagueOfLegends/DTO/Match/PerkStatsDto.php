<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class PerkStatsDto
{
    private int $defense;
    private int $flex;
    private int $offense;

    public function getDefense(): int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): self
    {
        $this->defense = $defense;

        return $this;
    }

    public function getFlex(): int
    {
        return $this->flex;
    }

    public function setFlex(int $flex): self
    {
        $this->flex = $flex;

        return $this;
    }

    public function getOffense(): int
    {
        return $this->offense;
    }

    public function setOffense(int $offense): self
    {
        $this->offense = $offense;

        return $this;
    }
}
