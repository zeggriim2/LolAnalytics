<?php

namespace App\Entity;

use App\Repository\GameTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameTypeRepository::class)]
class GameType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $gameType = null;

    #[ORM\Column(length: 150)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameType(): ?string
    {
        return $this->gameType;
    }

    public function setGameType(string $gameType): self
    {
        $this->gameType = $gameType;

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
}
