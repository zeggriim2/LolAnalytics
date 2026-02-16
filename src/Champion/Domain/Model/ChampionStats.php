<?php

declare(strict_types=1);

namespace App\Champion\Domain\Model;

final class ChampionStats
{
    public function __construct(
        private readonly float $hp,
        private readonly float $hpPerLevel,
        private readonly float $mp,
        private readonly float $mpPerLevel,
        private readonly float $moveSpeed,
        private readonly float $armor,
        private readonly float $armorPerLevel,
        private readonly float $spellBlock,
        private readonly float $spellBlockPerLevel,
        private readonly float $attackRange,
        private readonly float $hpRegen,
        private readonly float $hpRegenPerLevel,
        private readonly float $mpRegen,
        private readonly float $mpRegenPerLevel,
        private readonly float $crit,
        private readonly float $critPerLevel,
        private readonly float $attackDamage,
        private readonly float $attackDamagePerLevel,
        private readonly float $attackSpeed,
        private readonly float $attackSpeedPerLevel,
    ) {
    }

    public function hp(): float
    {
        return $this->hp;
    }

    public function hpPerLevel(): float
    {
        return $this->hpPerLevel;
    }

    public function mp(): float
    {
        return $this->mp;
    }

    public function mpPerLevel(): float
    {
        return $this->mpPerLevel;
    }

    public function moveSpeed(): float
    {
        return $this->moveSpeed;
    }

    public function armor(): float
    {
        return $this->armor;
    }

    public function armorPerLevel(): float
    {
        return $this->armorPerLevel;
    }

    public function spellBlock(): float
    {
        return $this->spellBlock;
    }

    public function spellBlockPerLevel(): float
    {
        return $this->spellBlockPerLevel;
    }

    public function attackRange(): float
    {
        return $this->attackRange;
    }

    public function hpRegen(): float
    {
        return $this->hpRegen;
    }

    public function hpRegenPerLevel(): float
    {
        return $this->hpRegenPerLevel;
    }

    public function mpRegen(): float
    {
        return $this->mpRegen;
    }

    public function mpRegenPerLevel(): float
    {
        return $this->mpRegenPerLevel;
    }

    public function crit(): float
    {
        return $this->crit;
    }

    public function critPerLevel(): float
    {
        return $this->critPerLevel;
    }

    public function attackDamage(): float
    {
        return $this->attackDamage;
    }

    public function attackDamagePerLevel(): float
    {
        return $this->attackDamagePerLevel;
    }

    public function attackSpeed(): float
    {
        return $this->attackSpeed;
    }

    public function attackSpeedPerLevel(): float
    {
        return $this->attackSpeedPerLevel;
    }
}
