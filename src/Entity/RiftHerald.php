<?php

namespace App\Entity;

use App\Repository\RiftHeraldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RiftHeraldRepository::class)]
class RiftHerald
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private $first;

    #[ORM\Column(type: 'integer')]
    private $kills;

    #[ORM\OneToOne(mappedBy: 'riftHerald', targetEntity: Team::class, cascade: ['persist', 'remove'])]
    private $team;

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
            $this->team->setRiftHerald(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $team && $team->getRiftHerald() !== $this) {
            $team->setRiftHerald($this);
        }

        $this->team = $team;

        return $this;
    }
}
