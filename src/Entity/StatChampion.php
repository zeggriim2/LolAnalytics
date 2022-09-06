<?php

namespace App\Entity;

use App\Repository\StatChampionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: StatChampionRepository::class)]
class StatChampion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(nullable: true, options: ["comment" => "Point de Vie"])]
    private ?int $hp = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Point de vie Obtenir par niveau"])]
    private ?int $hpperlevel = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Mana"])]
    private ?int $mp = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Mana par Niveau"])]
    private ?int $mpperlevel = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Vitesse de deplacement"])]
    private ?int $movespeed = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Armor"])]
    private ?int $armor = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Armor par niveau"])]
    private ?float $armorperlevel = null;

    #[ORM\Column(nullable: true)]
    private ?int $spellblock = null;

    #[ORM\Column(nullable: true)]
    private ?float $spellblockperlevel = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Distance d'attaque"])]
    private ?int $attackrange = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Regen Point de vie"])]
    private ?float $hpregen = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Regen Point de vie par niveau"])]
    private ?float $hpregenperlevel = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Regen Mana"])]
    private ?int $mpregen = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Regen Mana par niveau"])]
    private ?float $mpregenperlevel = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Critique"])]
    private ?int $crit = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Critique par niveau"])]
    private ?int $critperlevel = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Dommage d'attaque"])]
    private ?int $attackdamage = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Dommage d'attaque par niveau"])]
    private ?int $attackdamageperlevel = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Vitesse d'attaque par niveau"])]
    private ?float $attackspeedperlevel = null;

    #[ORM\Column(nullable: true, options: ["comment" => "Vitesse d'attaque"])]
    private ?float $attackspeed = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

    #[ORM\OneToOne(mappedBy: 'statChampion', cascade: ['persist', 'remove'])]
    private ?Champion $champion = null;

    public function __toString(): string
    {
        return (string) $this->getId();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(?int $hp): self
    {
        $this->hp = $hp;

        return $this;
    }

    public function getHpperlevel(): ?int
    {
        return $this->hpperlevel;
    }

    public function setHpperlevel(?int $hpperlevel): self
    {
        $this->hpperlevel = $hpperlevel;

        return $this;
    }

    public function getMp(): ?int
    {
        return $this->mp;
    }

    public function setMp(?int $mp): self
    {
        $this->mp = $mp;

        return $this;
    }

    public function getMpperlevel(): ?int
    {
        return $this->mpperlevel;
    }

    public function setMpperlevel(?int $mpperlevel): self
    {
        $this->mpperlevel = $mpperlevel;

        return $this;
    }

    public function getMovespeed(): ?int
    {
        return $this->movespeed;
    }

    public function setMovespeed(?int $movespeed): self
    {
        $this->movespeed = $movespeed;

        return $this;
    }

    public function getArmor(): ?int
    {
        return $this->armor;
    }

    public function setArmor(?int $armor): self
    {
        $this->armor = $armor;

        return $this;
    }

    public function getArmorperlevel(): ?float
    {
        return $this->armorperlevel;
    }

    public function setArmorperlevel(?float $armorperlevel): self
    {
        $this->armorperlevel = $armorperlevel;

        return $this;
    }

    public function getSpellblock(): ?int
    {
        return $this->spellblock;
    }

    public function setSpellblock(?int $spellblock): self
    {
        $this->spellblock = $spellblock;

        return $this;
    }

    public function getSpellblockperlevel(): ?float
    {
        return $this->spellblockperlevel;
    }

    public function setSpellblockperlevel(?float $spellblockperlevel): self
    {
        $this->spellblockperlevel = $spellblockperlevel;

        return $this;
    }

    public function getAttackrange(): ?int
    {
        return $this->attackrange;
    }

    public function setAttackrange(?int $attackrange): self
    {
        $this->attackrange = $attackrange;

        return $this;
    }

    public function getHpregen(): ?float
    {
        return $this->hpregen;
    }

    public function setHpregen(?float $hpregen): self
    {
        $this->hpregen = $hpregen;

        return $this;
    }

    public function getHpregenperlevel(): ?float
    {
        return $this->hpregenperlevel;
    }

    public function setHpregenperlevel(?float $hpregenperlevel): self
    {
        $this->hpregenperlevel = $hpregenperlevel;

        return $this;
    }

    public function getMpregen(): ?int
    {
        return $this->mpregen;
    }

    public function setMpregen(?int $mpregen): self
    {
        $this->mpregen = $mpregen;

        return $this;
    }

    public function getMpregenperlevel(): ?float
    {
        return $this->mpregenperlevel;
    }

    public function setMpregenperlevel(?float $mpregenperlevel): self
    {
        $this->mpregenperlevel = $mpregenperlevel;

        return $this;
    }

    public function getCrit(): ?int
    {
        return $this->crit;
    }

    public function setCrit(?int $crit): self
    {
        $this->crit = $crit;

        return $this;
    }

    public function getCritperlevel(): ?int
    {
        return $this->critperlevel;
    }

    public function setCritperlevel(?int $critperlevel): self
    {
        $this->critperlevel = $critperlevel;

        return $this;
    }

    public function getAttackdamage(): ?int
    {
        return $this->attackdamage;
    }

    public function setAttackdamage(?int $attackdamage): self
    {
        $this->attackdamage = $attackdamage;

        return $this;
    }

    public function getAttackdamageperlevel(): ?int
    {
        return $this->attackdamageperlevel;
    }

    public function setAttackdamageperlevel(?int $attackdamageperlevel): self
    {
        $this->attackdamageperlevel = $attackdamageperlevel;

        return $this;
    }

    public function getAttackspeedperlevel(): ?float
    {
        return $this->attackspeedperlevel;
    }

    public function setAttackspeedperlevel(?float $attackspeedperlevel): self
    {
        $this->attackspeedperlevel = $attackspeedperlevel;

        return $this;
    }

    public function getAttackspeed(): ?float
    {
        return $this->attackspeed;
    }

    public function setAttackspeed(?float $attackspeed): self
    {
        $this->attackspeed = $attackspeed;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getChampion(): ?Champion
    {
        return $this->champion;
    }

    public function setChampion(?Champion $champion): self
    {
        // unset the owning side of the relation if necessary
        if ($champion === null && $this->champion !== null) {
            $this->champion->setStatChampion(null);
        }

        // set the owning side of the relation if necessary
        if ($champion !== null && $champion->getStatChampion() !== $this) {
            $champion->setStatChampion($this);
        }

        $this->champion = $champion;

        return $this;
    }
}
