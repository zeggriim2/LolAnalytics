<?php

namespace App\Entity;

use App\Repository\RencontreRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RencontreRepository::class)]
#[UniqueEntity(fields: "gameId", message: 'gameId is already exist')]
class Rencontre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT, unique: true)]
    private string|int $gameId;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $gameCreation;

    #[ORM\Column(type: Types::INTEGER)]
    private int $gameDuration;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $gameMode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $gameVersion = null;

    /**
     * @var Collection<int, Team>
     */
    #[ORM\OneToMany(mappedBy: 'rencontre', targetEntity: Team::class)]
    private Collection $teams;

    /**
     * @var Collection<int, Invocateur>
     */
    #[ORM\ManyToMany(targetEntity: Invocateur::class, inversedBy: 'rencontres', )]
    private Collection$invocateurs;

    #[ORM\ManyToOne(targetEntity: Map::class, inversedBy: 'rencontres')]
    private ?Map $map;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->invocateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameId(): int|string
    {
        return $this->gameId;
    }

    /**
     * @return $this
     */
    public function setGameId(int|string $gameId): self
    {
        $this->gameId = $gameId;

        return $this;
    }

    public function getGameCreation(): ?\DateTimeInterface
    {
        return $this->gameCreation;
    }

    public function setGameCreation(DateTimeImmutable $gameCreation): self
    {
        $this->gameCreation = $gameCreation;

        return $this;
    }

    public function getGameDuration(): ?int
    {
        return $this->gameDuration;
    }

    public function setGameDuration(int $gameDuration): self
    {
        $this->gameDuration = $gameDuration;

        return $this;
    }

    public function getGameMode(): ?string
    {
        return $this->gameMode;
    }

    public function setGameMode(?string $gameMode): self
    {
        $this->gameMode = $gameMode;

        return $this;
    }

    public function getGameVersion(): ?string
    {
        return $this->gameVersion;
    }

    public function setGameVersion(?string $gameVersion): self
    {
        $this->gameVersion = $gameVersion;

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
