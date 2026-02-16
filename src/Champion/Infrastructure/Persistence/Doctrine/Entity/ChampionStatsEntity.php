<?php

declare(strict_types=1);

namespace App\Champion\Infrastructure\Persistence\Doctrine\Entity;

use App\Champion\Domain\Model\ChampionStats;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'champion_stats')]
class ChampionStatsEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'stats')]
    #[ORM\JoinColumn(name: 'riot_id', referencedColumnName: 'riot_id')]
    #[ORM\JoinColumn(name: 'version', referencedColumnName: 'version')]
    private ChampionEntity $champion;

    #[ORM\Column(type: Types::FLOAT)]
    private float $hp;

    #[ORM\Column(type: Types::FLOAT)]
    private float $hpPerLevel;

    #[ORM\Column(type: Types::FLOAT)]
    private float $mp;

    #[ORM\Column(type: Types::FLOAT)]
    private float $mpPerLevel;

    #[ORM\Column(type: Types::FLOAT)]
    private float $moveSpeed;

    #[ORM\Column(type: Types::FLOAT)]
    private float $armor;

    #[ORM\Column(type: Types::FLOAT)]
    private float $armorPerLevel;

    #[ORM\Column(type: Types::FLOAT)]
    private float $spellBlock;

    #[ORM\Column(type: Types::FLOAT)]
    private float $spellBlockPerLevel;

    #[ORM\Column(type: Types::FLOAT)]
    private float $attackRange;

    #[ORM\Column(type: Types::FLOAT)]
    private float $hpRegen;

    #[ORM\Column(type: Types::FLOAT)]
    private float $hpRegenPerLevel;

    #[ORM\Column(type: Types::FLOAT)]
    private float $mpRegen;

    #[ORM\Column(type: Types::FLOAT)]
    private float $mpRegenPerLevel;

    #[ORM\Column(type: Types::FLOAT)]
    private float $crit;

    #[ORM\Column(type: Types::FLOAT)]
    private float $critPerLevel;

    #[ORM\Column(type: Types::FLOAT)]
    private float $attackDamage;

    #[ORM\Column(type: Types::FLOAT)]
    private float $attackDamagePerLevel;

    #[ORM\Column(type: Types::FLOAT)]
    private float $attackSpeed;

    #[ORM\Column(type: Types::FLOAT)]
    private float $attackSpeedPerLevel;

    public static function fromDomain(ChampionStats $stats, ChampionEntity $champion): self
    {
        $e = new self();
        $e->champion = $champion;
        $e->hp = $stats->hp();
        $e->hpPerLevel = $stats->hpPerLevel();
        $e->mp = $stats->mp();
        $e->mpPerLevel = $stats->mpPerLevel();
        $e->moveSpeed = $stats->moveSpeed();
        $e->armor = $stats->armor();
        $e->armorPerLevel = $stats->armorPerLevel();
        $e->spellBlock = $stats->spellBlock();
        $e->spellBlockPerLevel = $stats->spellBlockPerLevel();
        $e->attackRange = $stats->attackRange();
        $e->hpRegen = $stats->hpRegen();
        $e->hpRegenPerLevel = $stats->hpRegenPerLevel();
        $e->mpRegen = $stats->mpRegen();
        $e->mpRegenPerLevel = $stats->mpRegenPerLevel();
        $e->crit = $stats->crit();
        $e->critPerLevel = $stats->critPerLevel();
        $e->attackDamage = $stats->attackDamage();
        $e->attackDamagePerLevel = $stats->attackDamagePerLevel();
        $e->attackSpeed = $stats->attackSpeed();
        $e->attackSpeedPerLevel = $stats->attackSpeedPerLevel();

        return $e;
    }

    public function toDomain(): ChampionStats
    {
        return new ChampionStats(
            hp: $this->hp,
            hpPerLevel: $this->hpPerLevel,
            mp: $this->mp,
            mpPerLevel: $this->mpPerLevel,
            moveSpeed: $this->moveSpeed,
            armor: $this->armor,
            armorPerLevel: $this->armorPerLevel,
            spellBlock: $this->spellBlock,
            spellBlockPerLevel: $this->spellBlockPerLevel,
            attackRange: $this->attackRange,
            hpRegen: $this->hpRegen,
            hpRegenPerLevel: $this->hpRegenPerLevel,
            mpRegen: $this->mpRegen,
            mpRegenPerLevel: $this->mpRegenPerLevel,
            crit: $this->crit,
            critPerLevel: $this->critPerLevel,
            attackDamage: $this->attackDamage,
            attackDamagePerLevel: $this->attackDamagePerLevel,
            attackSpeed: $this->attackSpeed,
            attackSpeedPerLevel: $this->attackSpeedPerLevel,
        );
    }
}
