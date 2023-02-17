<?php

namespace App\Entity;

use App\Repository\SpellRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpellRepository::class)]
class Spell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $idLol = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $tooltip = null;

    #[ORM\Column(length: 50)]
    private ?string $cooldownBurn = null;

    #[ORM\Column(length: 50)]
    private ?string $costBurn = null;

    #[ORM\Column]
    private array $range = [];

    #[ORM\Column(length: 50)]
    private ?string $rangeBurn = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'spells')]
    private ?Image $image = null;

    #[ORM\ManyToMany(targetEntity: Champion::class, mappedBy: 'spells')]
    private Collection $champions;

    public function __construct()
    {
        $this->champions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdLol(): ?string
    {
        return $this->idLol;
    }

    public function setIdLol(string $idLol): self
    {
        $this->idLol = $idLol;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTooltip(): ?string
    {
        return $this->tooltip;
    }

    public function setTooltip(string $tooltip): self
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    public function getCooldownBurn(): ?string
    {
        return $this->cooldownBurn;
    }

    public function setCooldownBurn(string $cooldownBurn): self
    {
        $this->cooldownBurn = $cooldownBurn;

        return $this;
    }

    public function getCostBurn(): ?string
    {
        return $this->costBurn;
    }

    public function setCostBurn(string $costBurn): self
    {
        $this->costBurn = $costBurn;

        return $this;
    }

    public function getRange(): array
    {
        return $this->range;
    }

    public function setRange(array $range): self
    {
        $this->range = $range;

        return $this;
    }

    public function getRangeBurn(): ?string
    {
        return $this->rangeBurn;
    }

    public function setRangeBurn(string $rangeBurn): self
    {
        $this->rangeBurn = $rangeBurn;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

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
            $champion->addSpell($this);
        }

        return $this;
    }

    public function removeChampion(Champion $champion): self
    {
        if ($this->champions->removeElement($champion)) {
            $champion->removeSpell($this);
        }

        return $this;
    }
}
