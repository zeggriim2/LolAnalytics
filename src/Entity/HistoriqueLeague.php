<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\HistoriqueLeagueRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: HistoriqueLeagueRepository::class)]
class HistoriqueLeague
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $leagueId;

    #[ORM\Column(type: 'string', length: 100)]
    private string $tier;

    #[ORM\Column(type: 'string', length: 10)]
    private string $rank;

    #[ORM\Column(type: 'integer')]
    private int $leaguePoint;

    #[ORM\ManyToOne(targetEntity: Invocateur::class, inversedBy: 'historiqueLeagues')]
    private ?Invocateur $invocateur;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createAt;

    #[ORM\Column(type: 'integer')]
    private int $wins;

    #[ORM\Column(type: 'integer')]
    private int $losses;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTier(): ?string
    {
        return $this->tier;
    }

    public function setTier(string $tier): self
    {
        $this->tier = $tier;

        return $this;
    }

    public function getRank(): ?string
    {
        return $this->rank;
    }

    public function setRank(string $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getLeaguePoint(): ?int
    {
        return $this->leaguePoint;
    }

    public function setLeaguePoint(int $leaguePoint): self
    {
        $this->leaguePoint = $leaguePoint;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): self
    {
        $this->createAt = $createAt;

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

    public function getLeagueId(): ?string
    {
        return $this->leagueId;
    }

    public function setLeagueId(string $leagueId): self
    {
        $this->leagueId = $leagueId;

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

    public function getLosses(): ?int
    {
        return $this->losses;
    }

    public function setLosses(int $losses): self
    {
        $this->losses = $losses;

        return $this;
    }
}
