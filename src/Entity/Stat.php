<?php

namespace App\Entity;

use App\Repository\StatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatRepository::class)]
class Stat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $hp = null;

    #[ORM\Column]
    private ?int $hpperlevel = null;

    #[ORM\Column]
    private ?float $mp = null;

    #[ORM\Column]
    private ?float $mpperlevel = null;

    #[ORM\Column]
    private ?int $movespeed = null;

    #[ORM\Column]
    private ?int $armor = null;

    #[ORM\Column]
    private ?float $armorperlevel = null;

    #[ORM\Column]
    private ?float $spellblock = null;

    #[ORM\Column]
    private ?float $spellblockperlevel = null;

    #[ORM\Column]
    private ?int $attackrange = null;

    #[ORM\Column]
    private ?float $hpregen = null;

    #[ORM\Column]
    private ?float $hpregenperlevel = null;

    #[ORM\Column]
    private ?float $mpregen = null;

    #[ORM\Column]
    private ?float $mpregenperlevel = null;

    #[ORM\Column]
    private ?int $crit = null;

    #[ORM\Column]
    private ?int $critperlevel = null;

    #[ORM\Column]
    private ?int $attackdamage = null;

    #[ORM\Column]
    private ?float $attackdamageperlevel = null;

    #[ORM\Column]
    private ?float $attackspeedperlevel = null;

    #[ORM\Column]
    private ?float $attackspeed = null;

    #[ORM\OneToMany(mappedBy: 'stat', targetEntity: Champion::class)]
    private Collection $champions;

    public function __construct()
    {
        $this->champions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHp(): ?float
    {
        return $this->hp;
    }

    public function setHp(float $hp): self
    {
        $this->hp = $hp;

        return $this;
    }

    public function getHpperlevel(): ?int
    {
        return $this->hpperlevel;
    }

    public function setHpperlevel(int $hpperlevel): self
    {
        $this->hpperlevel = $hpperlevel;

        return $this;
    }

    public function getMp(): ?float
    {
        return $this->mp;
    }

    public function setMp(float $mp): self
    {
        $this->mp = $mp;

        return $this;
    }

    public function getMpperlevel(): ?float
    {
        return $this->mpperlevel;
    }

    public function setMpperlevel(float $mpperlevel): self
    {
        $this->mpperlevel = $mpperlevel;

        return $this;
    }

    public function getMovespeed(): ?int
    {
        return $this->movespeed;
    }

    public function setMovespeed(int $movespeed): self
    {
        $this->movespeed = $movespeed;

        return $this;
    }

    public function getArmor(): ?int
    {
        return $this->armor;
    }

    public function setArmor(int $armor): self
    {
        $this->armor = $armor;

        return $this;
    }

    public function getArmorperlevel(): ?float
    {
        return $this->armorperlevel;
    }

    public function setArmorperlevel(float $armorperlevel): self
    {
        $this->armorperlevel = $armorperlevel;

        return $this;
    }

    public function getSpellblock(): ?float
    {
        return $this->spellblock;
    }

    public function setSpellblock(float $spellblock): self
    {
        $this->spellblock = $spellblock;

        return $this;
    }

    public function getSpellblockperlevel(): ?float
    {
        return $this->spellblockperlevel;
    }

    public function setSpellblockperlevel(float $spellblockperlevel): self
    {
        $this->spellblockperlevel = $spellblockperlevel;

        return $this;
    }

    public function getAttackrange(): ?int
    {
        return $this->attackrange;
    }

    public function setAttackrange(int $attackrange): self
    {
        $this->attackrange = $attackrange;

        return $this;
    }

    public function getHpregen(): ?float
    {
        return $this->hpregen;
    }

    public function setHpregen(float $hpregen): self
    {
        $this->hpregen = $hpregen;

        return $this;
    }

    public function getHpregenperlevel(): ?float
    {
        return $this->hpregenperlevel;
    }

    public function setHpregenperlevel(float $hpregenperlevel): self
    {
        $this->hpregenperlevel = $hpregenperlevel;

        return $this;
    }

    public function getMpregen(): ?float
    {
        return $this->mpregen;
    }

    public function setMpregen(float $mpregen): self
    {
        $this->mpregen = $mpregen;

        return $this;
    }

    public function getMpregenperlevel(): ?float
    {
        return $this->mpregenperlevel;
    }

    public function setMpregenperlevel(float $mpregenperlevel): self
    {
        $this->mpregenperlevel = $mpregenperlevel;

        return $this;
    }

    public function getCrit(): ?int
    {
        return $this->crit;
    }

    public function setCrit(int $crit): self
    {
        $this->crit = $crit;

        return $this;
    }

    public function getCritperlevel(): ?int
    {
        return $this->critperlevel;
    }

    public function setCritperlevel(int $critperlevel): self
    {
        $this->critperlevel = $critperlevel;

        return $this;
    }

    public function getAttackdamage(): ?int
    {
        return $this->attackdamage;
    }

    public function setAttackdamage(int $attackdamage): self
    {
        $this->attackdamage = $attackdamage;

        return $this;
    }

    public function getAttackdamageperlevel(): ?float
    {
        return $this->attackdamageperlevel;
    }

    public function setAttackdamageperlevel(float $attackdamageperlevel): self
    {
        $this->attackdamageperlevel = $attackdamageperlevel;

        return $this;
    }

    public function getAttackspeedperlevel(): ?float
    {
        return $this->attackspeedperlevel;
    }

    public function setAttackspeedperlevel(float $attackspeedperlevel): self
    {
        $this->attackspeedperlevel = $attackspeedperlevel;

        return $this;
    }

    public function getAttackspeed(): ?float
    {
        return $this->attackspeed;
    }

    public function setAttackspeed(float $attackspeed): self
    {
        $this->attackspeed = $attackspeed;

        return $this;
    }

    /**
     * @return Collection<int, Champion>
     */
    public function getChampions(): Collection
    {
        return $this->champions;
    }

    public function addChampion(Champion $champion): self
    {
        if (!$this->champions->contains($champion)) {
            $this->champions->add($champion);
            $champion->setStat($this);
        }

        return $this;
    }

    public function removeChampion(Champion $champion): self
    {
        if ($this->champions->removeElement($champion)) {
            // set the owning side to null (unless already changed)
            if ($champion->getStat() === $this) {
                $champion->setStat(null);
            }
        }

        return $this;
    }
}
