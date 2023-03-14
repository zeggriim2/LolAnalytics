<?php

namespace App\Entity;

use App\Repository\LeagueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeagueRepository::class)]
#[ORM\HasLifecycleCallbacks]
class League
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING ,length: 125)]
    private ?string $leagueId = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $leaguePoints = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $wins = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $looses = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $veteran = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $inactive = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $freshBlood = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $hotStreak = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $actif = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'leagues')]
    private ?DivisionLeague $divisionLeague = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'leagues')]
    private ?TierLeague $tierLeague = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'leagues')]
    private ?QueueLeague $queueLeague = null;

    #[ORM\ManyToOne(inversedBy: 'leagues')]
    private ?Summoner $summoner = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\PrePersist]
    public function setCreated(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function setLeaguePoints(int $leaguePoints): self
    {
        $this->leaguePoints = $leaguePoints;

        return $this;
    }

    public function getWins(): ?int
    {
        return $this->wins;
    }

    public function setWins(int $wins): self
    {
        $this->wins = $wins;

        return $this;
    }

    public function getLooses(): ?int
    {
        return $this->looses;
    }

    public function setLooses(int $looses): self
    {
        $this->looses = $looses;

        return $this;
    }

    public function isVeteran(): ?bool
    {
        return $this->veteran;
    }

    public function setVeteran(bool $veteran): self
    {
        $this->veteran = $veteran;

        return $this;
    }

    public function isInactive(): ?bool
    {
        return $this->inactive;
    }

    public function setInactive(bool $inactive): self
    {
        $this->inactive = $inactive;

        return $this;
    }

    public function isFreshBlood(): ?bool
    {
        return $this->freshBlood;
    }

    public function setFreshBlood(bool $freshBlood): self
    {
        $this->freshBlood = $freshBlood;

        return $this;
    }

    public function isHotStreak(): ?bool
    {
        return $this->hotStreak;
    }

    public function setHotStreak(bool $hotStreak): self
    {
        $this->hotStreak = $hotStreak;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getDivisionLeague(): ?DivisionLeague
    {
        return $this->divisionLeague;
    }

    public function setDivisionLeague(?DivisionLeague $divisionLeague): self
    {
        $this->divisionLeague = $divisionLeague;

        return $this;
    }

    public function getTierLeague(): ?TierLeague
    {
        return $this->tierLeague;
    }

    public function setTierLeague(?TierLeague $tierLeague): self
    {
        $this->tierLeague = $tierLeague;

        return $this;
    }

    public function getQueueLeague(): ?QueueLeague
    {
        return $this->queueLeague;
    }

    public function setQueueLeague(?QueueLeague $queueLeague): self
    {
        $this->queueLeague = $queueLeague;

        return $this;
    }

    public function getSummoner(): ?Summoner
    {
        return $this->summoner;
    }

    public function setSummoner(?Summoner $summoner): self
    {
        $this->summoner = $summoner;

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
