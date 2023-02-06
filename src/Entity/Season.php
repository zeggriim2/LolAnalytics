<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeasonRepository::class)]
class Season
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?int $idLol = null;

    #[ORM\Column(length: 50)]
    private ?string $season = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdLol(): ?int
    {
        return $this->idLol;
    }

    public function setIdLol(int $idLol): self
    {
        $this->idLol = $idLol;

        return $this;
    }

    public function getSeason(): ?string
    {
        return $this->season;
    }

    public function setSeason(string $season): self
    {
        $this->season = $season;

        return $this;
    }
}
