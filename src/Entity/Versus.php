<?php

namespace App\Entity;

use App\Repository\VersusRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: VersusRepository::class)]
class Versus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $dateVersus = null;

    #[ORM\ManyToOne(inversedBy: 'versusEquipe1')]
    private ?Equipe $equipe1 = null;

    #[ORM\ManyToOne(inversedBy: 'versusEquipe2')]
    private ?Equipe $equipe2 = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $ScoreEquipe1 = 0;

    #[ORM\Column(type: Types::INTEGER)]
    private int $ScoreEquipe2 = 0;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateVersus(): ?\DateTimeImmutable
    {
        return $this->dateVersus;
    }

    public function setDateVersus(\DateTimeImmutable $dateVersus): self
    {
        $this->dateVersus = $dateVersus;

        return $this;
    }

    public function getEquipe1(): ?Equipe
    {
        return $this->equipe1;
    }

    public function setEquipe1(?Equipe $equipe1): self
    {
        $this->equipe1 = $equipe1;

        return $this;
    }

    public function getEquipe2(): ?Equipe
    {
        return $this->equipe2;
    }

    public function setEquipe2(?Equipe $equipe2): self
    {
        $this->equipe2 = $equipe2;

        return $this;
    }

    public function getScoreEquipe1(): int
    {
        return $this->ScoreEquipe1;
    }

    public function setScoreEquipe1(int $ScoreEquipe1): self
    {
        $this->ScoreEquipe1 = $ScoreEquipe1;

        return $this;
    }

    public function getScoreEquipe2(): int
    {
        return $this->ScoreEquipe2;
    }

    public function setScoreEquipe2(int $ScoreEquipe2): self
    {
        $this->ScoreEquipe2 = $ScoreEquipe2;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
