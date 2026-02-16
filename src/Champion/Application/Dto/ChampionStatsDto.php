<?php

declare(strict_types=1);

namespace App\Champion\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Zeggriim\RiotApiDataDragon\DataDragon\Dto\Champion\ChampionStats as BundleChampionStats;

final readonly class ChampionStatsDto
{
    public function __construct(
        #[Assert\GreaterThanOrEqual(0)]
        public float $hp,
        #[Assert\GreaterThanOrEqual(0)]
        public float $hpPerLevel,
        #[Assert\GreaterThanOrEqual(0)]
        public float $mp,
        #[Assert\GreaterThanOrEqual(0)]
        public float $mpPerLevel,
        #[Assert\GreaterThan(0)]
        public float $moveSpeed,
        #[Assert\GreaterThanOrEqual(0)]
        public float $armor,
        #[Assert\GreaterThanOrEqual(0)]
        public float $armorPerLevel,
        #[Assert\GreaterThanOrEqual(0)]
        public float $spellBlock,
        #[Assert\GreaterThanOrEqual(0)]
        public float $spellBlockPerLevel,
        #[Assert\GreaterThanOrEqual(0)]
        public float $attackRange,
        #[Assert\GreaterThanOrEqual(0)]
        public float $hpRegen,
        #[Assert\GreaterThanOrEqual(0)]
        public float $hpRegenPerLevel,
        #[Assert\GreaterThanOrEqual(0)]
        public float $mpRegen,
        #[Assert\GreaterThanOrEqual(0)]
        public float $mpRegenPerLevel,
        #[Assert\GreaterThanOrEqual(0)]
        public float $crit,
        #[Assert\GreaterThanOrEqual(0)]
        public float $critPerLevel,
        #[Assert\GreaterThanOrEqual(0)]
        public float $attackDamage,
        #[Assert\GreaterThanOrEqual(0)]
        public float $attackDamagePerLevel,
        #[Assert\GreaterThanOrEqual(0)]
        public float $attackSpeed,
        public float $attackSpeedPerLevel,
    ) {
    }

    public static function fromBundleDto(BundleChampionStats $stats): self
    {
        return new self(
            hp: (float) $stats->hp,
            hpPerLevel: (float) $stats->hpperlevel,
            mp: (float) $stats->mp,
            mpPerLevel: (float) $stats->mpperlevel,
            moveSpeed: (float) $stats->movespeed,
            armor: (float) $stats->armor,
            armorPerLevel: (float) $stats->armorperlevel,
            spellBlock: (float) $stats->spellblock,
            spellBlockPerLevel: (float) $stats->spellblockperlevel,
            attackRange: (float) $stats->attackrange,
            hpRegen: (float) $stats->hpregen,
            hpRegenPerLevel: (float) $stats->hpregenperlevel,
            mpRegen: (float) $stats->mpregen,
            mpRegenPerLevel: (float) $stats->mpregenperlevel,
            crit: (float) $stats->crit,
            critPerLevel: (float) $stats->critperlevel,
            attackDamage: (float) $stats->attackdamage,
            attackDamagePerLevel: (float) $stats->attackdamageperlevel,
            attackSpeed: (float) $stats->attackspeed,
            attackSpeedPerLevel: (float) $stats->attackspeedperlevel,
        );
    }
}
