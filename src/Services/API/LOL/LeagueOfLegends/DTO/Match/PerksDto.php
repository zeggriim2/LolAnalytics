<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class PerksDto
{
    private PerkStatsDto $statPerks;
    /**
     * @var PerkStyleDto[]
     */
    private array $styles;

    public function getStatPerks(): PerkStatsDto
    {
        return $this->statPerks;
    }

    public function setStatPerks(PerkStatsDto $statPerks): self
    {
        $this->statPerks = $statPerks;

        return $this;
    }

    /**
     * @return PerkStyleDto[]
     */
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * @param PerkStyleDto[] $styles
     */
    public function setStyles(array $styles): self
    {
        $this->styles = $styles;

        return $this;
    }
}
