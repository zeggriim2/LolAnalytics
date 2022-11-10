<?php

namespace App\Entity;

use App\Repository\LeagueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: LeagueRepository::class)]
class League
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $leagueId = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $leaguePoints = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $wins = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $losses = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $hotStreak = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $veteran = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $freshBlood = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $inactive = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'leagues')]
    private ?Invocateur $invocateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeagueId(): ?string
    {
        return $this->leagueId;
    }

    public function setLeagueId(string $leagueId): self
    {
        $this->leagueId = $leagueId;

        return $this;
    }

    public function getLeaguePoints(): ?int
    {
        return $this->leaguePoints;
    }

    public function setLeaguePoints(?int $leaguePoints): self
    {
        $this->leaguePoints = $leaguePoints;

        return $this;
    }

    public function getWins(): ?int
    {
        return $this->wins;
    }

    public function setWins(?int $wins): self
    {
        $this->wins = $wins;

        return $this;
    }

    public function getLosses(): ?int
    {
        return $this->losses;
    }

    public function setLosses(?int $losses): self
    {
        $this->losses = $losses;

        return $this;
    }

    public function getHotStreak(): ?bool
    {
        return $this->hotStreak;
    }

    public function setHotStreak(?bool $hotStreak): self
    {
        $this->hotStreak = $hotStreak;

        return $this;
    }

    public function getVeteran(): ?bool
    {
        return $this->veteran;
    }

    public function setVeteran(?bool $veteran): self
    {
        $this->veteran = $veteran;

        return $this;
    }

    public function getFreshBlood(): ?bool
    {
        return $this->freshBlood;
    }

    public function setFreshBlood(?bool $freshBlood): self
    {
        $this->freshBlood = $freshBlood;

        return $this;
    }

    public function getInactive(): ?bool
    {
        return $this->inactive;
    }

    public function setInactive(?bool $inactive): self
    {
        $this->inactive = $inactive;

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

    public function getInvocateur(): ?Invocateur
    {
        return $this->invocateur;
    }

    public function setInvocateur(?Invocateur $invocateur): self
    {
        $this->invocateur = $invocateur;

        return $this;
    }
}
