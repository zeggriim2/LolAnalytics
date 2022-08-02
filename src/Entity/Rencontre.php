<?php

namespace App\Entity;

use App\Repository\RencontreRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RencontreRepository::class)]
class Rencontre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'bigint')]
    private $gameId;

    #[ORM\Column(type: 'datetime')]
    private $gameCreation;

    #[ORM\Column(type: 'datetime')]
    private $gameDuration;

    #[ORM\OneToMany(mappedBy: 'rencontre', targetEntity: Team::class)]
    private $teams;

    #[ORM\ManyToMany(targetEntity: Invocateur::class, inversedBy: 'rencontres')]
    private $invocateurs;

    #[ORM\ManyToOne(targetEntity: Map::class, inversedBy: 'rencontres')]
    private $map;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->invocateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameId(): ?string
    {
        return $this->gameId;
    }

    public function setGameId(string $gameId): self
    {
        $this->gameId = $gameId;

        return $this;
    }

    public function getGameCreation(): ?\DateTimeInterface
    {
        return $this->gameCreation;
    }

    public function setGameCreation(DateTimeImmutable|int $gameCreation): self
    {
        if($gameCreation instanceof DateTimeImmutable) {
            $this->gameCreation = $gameCreation;
        } elseif (is_int($gameCreation)){
            $gameCreation = $gameCreation / 1000;
            $this->gameCreation = new DateTimeImmutable("@{$gameCreation}");
        }

        return $this;
    }

    public function getGameDuration(): ?\DateTimeInterface
    {
        return $this->gameDuration;
    }

    public function setGameDuration(DateTimeImmutable|int $gameDuration): self
    {
        if($gameDuration instanceof DateTimeImmutable) {
            $this->gameDuration = $gameDuration;
        } elseif (is_int($gameDuration)) {
            $gameDuration = $gameDuration / 1000;
            $this->gameDuration = new DateTimeImmutable("@{$gameDuration}");
        }

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setRencontre($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getRencontre() === $this) {
                $team->setRencontre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invocateur>
     */
    public function getInvocateurs(): Collection
    {
        return $this->invocateurs;
    }

    public function addInvocateur(Invocateur $invocateur): self
    {
        if (!$this->invocateurs->contains($invocateur)) {
            $this->invocateurs[] = $invocateur;
        }

        return $this;
    }

    public function removeInvocateur(Invocateur $invocateur): self
    {
        $this->invocateurs->removeElement($invocateur);

        return $this;
    }

    public function getMap(): ?Map
    {
        return $this->map;
    }

    public function setMap(?Map $map): self
    {
        $this->map = $map;

        return $this;
    }
}
