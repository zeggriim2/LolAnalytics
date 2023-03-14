<?php

namespace App\Entity;

use App\Repository\SummonerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SummonerRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Summoner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 56)]
    private ?string $accountId = null;

    #[ORM\Column]
    private ?int $profileIconId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 63)]
    private ?string $idLol = null;

    #[ORM\Column(length: 78)]
    private ?string $puuid = null;

    #[ORM\Column]
    private ?int $sumonerLevel = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedDate = null;

    #[ORM\OneToMany(mappedBy: 'summoner', targetEntity: League::class)]
    private Collection $leagues;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $revisionDate = null;

    public function __construct()
    {
        $this->leagues = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function setCreatedValue(): void
    {
        $this->createdDate = new \DateTimeImmutable();
        $this->updatedDate = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdateValue(): void
    {
        $this->updatedDate = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function setAccountId(string $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function getProfileIconId(): ?int
    {
        return $this->profileIconId;
    }

    public function setProfileIconId(int $profileIconId): self
    {
        $this->profileIconId = $profileIconId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdLol(): ?string
    {
        return $this->idLol;
    }

    public function setIdLol(string $idLol): self
    {
        $this->idLol = $idLol;

        return $this;
    }

    public function getPuuid(): ?string
    {
        return $this->puuid;
    }

    public function setPuuid(string $puuid): self
    {
        $this->puuid = $puuid;

        return $this;
    }

    public function getSumonerLevel(): ?int
    {
        return $this->sumonerLevel;
    }

    public function setSumonerLevel(int $sumonerLevel): self
    {
        $this->sumonerLevel = $sumonerLevel;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeImmutable
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeImmutable $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeImmutable
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(\DateTimeImmutable $updatedDate): self
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * @return Collection<int, League>
     */
    public function getLeagues(): Collection
    {
        return $this->leagues;
    }

    public function addLeague(League $league): self
    {
        if (!$this->leagues->contains($league)) {
            $this->leagues->add($league);
            $league->setSummoner($this);
        }

        return $this;
    }

    public function removeLeague(League $league): self
    {
        if ($this->leagues->removeElement($league)) {
            // set the owning side to null (unless already changed)
            if ($league->getSummoner() === $this) {
                $league->setSummoner(null);
            }
        }

        return $this;
    }

    public function getRevisionDate(): ?\DateTimeImmutable
    {
        return $this->revisionDate;
    }

    public function setRevisionDate(\DateTimeImmutable $revisionDate): self
    {
        $this->revisionDate = $revisionDate;

        return $this;
    }
}
