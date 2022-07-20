<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class PerkStyleSelectionDto
{
    private int $perk;
    private int $var1;
    private int $var2;
    private int $var3;

    public function getPerk(): int
    {
        return $this->perk;
    }

    public function setPerk(int $perk): self
    {
        $this->perk = $perk;

        return $this;
    }

    public function getVar1(): int
    {
        return $this->var1;
    }

    public function setVar1(int $var1): self
    {
        $this->var1 = $var1;

        return $this;
    }

    public function getVar2(): int
    {
        return $this->var2;
    }

    public function setVar2(int $var2): self
    {
        $this->var2 = $var2;

        return $this;
    }

    public function getVar3(): int
    {
        return $this->var3;
    }

    public function setVar3(int $var3): self
    {
        $this->var3 = $var3;

        return $this;
    }
}
