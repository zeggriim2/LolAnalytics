<?php

namespace App\Entity;

use App\Repository\DragonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DragonRepository::class)]
class Dragon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'boolean')]
    private bool $first;

    #[ORM\Column(type: 'integer')]
    private int $kills;

    #[ORM\OneToOne(mappedBy: 'dragon', targetEntity: Team::class, cascade: ['persist', 'remove'])]
    private ?Team $team;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isFirst(): ?bool
    {
        return $this->first;
    }

    public function setFirst(bool $first): self
    {
        $this->first = $first;

        return $this;
    }

    public function getKills(): ?int
    {
        return $this->kills;
    }

    public function setKills(int $kills): self
    {
        $this->kills = $kills;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        // unset the owning side of the relation if necessary
        if (null === $team && null !== $this->team) {
            $this->team->setDragon(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $team && $team->getDragon() !== $this) {
            $team->setDragon($this);
        }

        $this->team = $team;

        return $this;
    }
}
