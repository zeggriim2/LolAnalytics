<?php

namespace App\Entity;

use App\Repository\InfoChampionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfoChampionRepository::class)]
class InfoChampion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $attack;

    #[ORM\Column(type: 'integer')]
    private $defense;

    #[ORM\Column(type: 'integer')]
    private $magic;

    #[ORM\Column(type: 'integer')]
    private $difficulty;

    #[ORM\OneToOne(inversedBy: 'infoChampion', targetEntity: Champion::class, cascade: ['persist', 'remove'])]
    private $champion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(int $attack): self
    {
        $this->attack = $attack;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): self
    {
        $this->defense = $defense;

        return $this;
    }

    public function getMagic(): ?int
    {
        return $this->magic;
    }

    public function setMagic(int $magic): self
    {
        $this->magic = $magic;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getChampion(): ?Champion
    {
        return $this->champion;
    }

    public function setChampion(?Champion $champion): self
    {
        $this->champion = $champion;

        return $this;
    }
}
