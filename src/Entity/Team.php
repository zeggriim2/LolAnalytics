<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $teamIdLol;

    #[ORM\Column(type: 'boolean')]
    private bool $win;

    #[ORM\Column(type: 'integer')]
    private int $ban1ChampionId;

    #[ORM\Column(type: 'integer')]
    private int $ban2ChampionId;

    #[ORM\Column(type: 'integer')]
    private int $ban3ChampionId;

    #[ORM\Column(type: 'integer')]
    private int $ban4ChampionId;

    #[ORM\Column(type: 'integer')]
    private int $ban5ChampionId;

    #[ORM\OneToOne(inversedBy: 'team', targetEntity: Baron::class, cascade: ['persist', 'remove'])]
    private ?Baron $baron;

    #[ORM\OneToOne(inversedBy: 'team', targetEntity: Dragon::class, cascade: ['persist', 'remove'])]
    private ?Dragon $dragon;

    #[ORM\OneToOne(inversedBy: 'team', targetEntity: Inhibitor::class, cascade: ['persist', 'remove'])]
    private ?Inhibitor $inhibitor;

    #[ORM\OneToOne(inversedBy: 'team', targetEntity: RiftHerald::class, cascade: ['persist', 'remove'])]
    private ?RiftHerald $riftHerald;

    #[ORM\OneToOne(inversedBy: 'team', targetEntity: Tower::class, cascade: ['persist', 'remove'])]
    private ?Tower $tower;

    #[ORM\ManyToOne(targetEntity: Rencontre::class, inversedBy: 'teams')]
    private ?Rencontre $rencontre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamIdLol(): ?int
    {
        return $this->teamIdLol;
    }

    public function setTeamIdLol(int $teamIdLol): self
    {
        $this->teamIdLol = $teamIdLol;

        return $this;
    }

    public function isWin(): ?bool
    {
        return $this->win;
    }

    public function setWin(bool $win): self
    {
        $this->win = $win;

        return $this;
    }

    public function getBan1ChampionId(): ?int
    {
        return $this->ban1ChampionId;
    }

    public function setBan1ChampionId(int $ban1ChampionId): self
    {
        $this->ban1ChampionId = $ban1ChampionId;

        return $this;
    }

    public function getBan2ChampionId(): ?int
    {
        return $this->ban2ChampionId;
    }

    public function setBan2ChampionId(int $ban2ChampionId): self
    {
        $this->ban2ChampionId = $ban2ChampionId;

        return $this;
    }

    public function getBan3ChampionId(): ?int
    {
        return $this->ban3ChampionId;
    }

    public function setBan3ChampionId(int $ban3ChampionId): self
    {
        $this->ban3ChampionId = $ban3ChampionId;

        return $this;
    }

    public function getBan4ChampionId(): ?int
    {
        return $this->ban4ChampionId;
    }

    public function setBan4ChampionId(int $ban4ChampionId): self
    {
        $this->ban4ChampionId = $ban4ChampionId;

        return $this;
    }

    public function getBan5ChampionId(): ?int
    {
        return $this->ban5ChampionId;
    }

    public function setBan5ChampionId(int $ban5ChampionId): self
    {
        $this->ban5ChampionId = $ban5ChampionId;

        return $this;
    }

    public function getBaron(): ?Baron
    {
        return $this->baron;
    }

    public function setBaron(?Baron $baron): self
    {
        $this->baron = $baron;

        return $this;
    }

    public function getDragon(): ?Dragon
    {
        return $this->dragon;
    }

    public function setDragon(?Dragon $dragon): self
    {
        $this->dragon = $dragon;

        return $this;
    }

    public function getInhibitor(): ?Inhibitor
    {
        return $this->inhibitor;
    }

    public function setInhibitor(?Inhibitor $inhibitor): self
    {
        $this->inhibitor = $inhibitor;

        return $this;
    }

    public function getRiftHerald(): ?RiftHerald
    {
        return $this->riftHerald;
    }

    public function setRiftHerald(?RiftHerald $riftHerald): self
    {
        $this->riftHerald = $riftHerald;

        return $this;
    }

    public function getTower(): ?Tower
    {
        return $this->tower;
    }

    public function setTower(?Tower $tower): self
    {
        $this->tower = $tower;

        return $this;
    }

    public function getRencontre(): ?Rencontre
    {
        return $this->rencontre;
    }

    public function setRencontre(?Rencontre $rencontre): self
    {
        $this->rencontre = $rencontre;

        return $this;
    }
}
