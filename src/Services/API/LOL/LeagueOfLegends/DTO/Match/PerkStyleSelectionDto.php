<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class PerkStyleSelectionDto
{
    private int $perk;
    private int $var1;
    private int $var2;
    private int $var3;

    /**
     * @return int
     */
    public function getPerk(): int
    {
        return $this->perk;
    }

    /**
     * @param int $perk
     * @return PerkStyleSelectionDto
     */
    public function setPerk(int $perk): PerkStyleSelectionDto
    {
        $this->perk = $perk;
        return $this;
    }

    /**
     * @return int
     */
    public function getVar1(): int
    {
        return $this->var1;
    }

    /**
     * @param int $var1
     * @return PerkStyleSelectionDto
     */
    public function setVar1(int $var1): PerkStyleSelectionDto
    {
        $this->var1 = $var1;
        return $this;
    }

    /**
     * @return int
     */
    public function getVar2(): int
    {
        return $this->var2;
    }

    /**
     * @param int $var2
     * @return PerkStyleSelectionDto
     */
    public function setVar2(int $var2): PerkStyleSelectionDto
    {
        $this->var2 = $var2;
        return $this;
    }

    /**
     * @return int
     */
    public function getVar3(): int
    {
        return $this->var3;
    }

    /**
     * @param int $var3
     * @return PerkStyleSelectionDto
     */
    public function setVar3(int $var3): PerkStyleSelectionDto
    {
        $this->var3 = $var3;
        return $this;
    }
}