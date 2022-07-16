<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class PerksDto
{
    private PerkStatsDto $statPerks;
    /**
     * @var PerkStyleDto[]
     */
    private array $styles;

    /**
     * @return PerkStatsDto
     */
    public function getStatPerks(): PerkStatsDto
    {
        return $this->statPerks;
    }

    /**
     * @param PerkStatsDto $statPerks
     * @return PerksDto
     */
    public function setStatPerks(PerkStatsDto $statPerks): PerksDto
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
     * @return PerksDto
     */
    public function setStyles(array $styles): PerksDto
    {
        $this->styles = $styles;
        return $this;
    }
}