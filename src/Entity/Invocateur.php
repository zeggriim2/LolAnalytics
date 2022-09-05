<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\InvocateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: InvocateurRepository::class)]
#[ORM\Table(options: ["comment"=> "Invocateur du jeux (Player)"])]
class Invocateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['comment' => 'Id de l\'invocateur'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true,
        options: ['comment' => 'PUUID de API donnée par API de LOL'])]
    private string $puuid;

    #[ORM\Column(type: 'integer', options: ['comment' => 'Niveau de l\'invocateur'])]
    private int $summonerLevel;

    #[ORM\Column(type: 'string', length: 255, options: ['comment' => 'Nom de l\'invocateur'])]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, options: ['comment' => 'ID donnée par API de LOL'])]
    private string $idLol;

    #[ORM\Column(type: 'integer', options: ['comment' => 'ID icon au profil de l\'invocateur'])]
    private int $profileIconId;

    #[ORM\Column(type: 'string', length: 255, options: ['comment' => 'ID de l\'account'])]
    private string $accoundId;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime_immutable', options: ['comment' => 'Date de création en Base'])]
    private \DateTimeImmutable $createdAt;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: 'datetime_immutable', nullable: true, options: ['comment' => 'Date de modif en Base'])]    private ?\DateTimeImmutable $updatedAt;

    /**
     * @var Collection<int, Rencontre>
     */
    #[ORM\ManyToMany(targetEntity: Rencontre::class, mappedBy: 'invocateurs')]
    #[ORM\Column(options: ['comment' =>''])]
    private Collection $rencontres;

    /**
     * @var Collection<int, HistoriqueLeague>
     */
    #[ORM\OneToMany(mappedBy: 'invocateur', targetEntity: HistoriqueLeague::class)]
    #[ORM\Column(options: ['comment' => 'Historique des Leagues de l\'invocateur'])]
    private Collection $historiqueLeagues;

    public function __construct()
    {
        $this->rencontres = new ArrayCollection();
        $this->historiqueLeagues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPuuid(): string
    {
        return $this->puuid;
    }

    public function setPuuid(string $puuid): self
    {
        $this->puuid = $puuid;

        return $this;
    }

    public function getSummonerLevel(): ?int
    {
        return $this->summonerLevel;
    }

    public function setSummonerLevel(int $summonerLevel): self
    {
        $this->summonerLevel = $summonerLevel;

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

    public function getProfileIconId(): ?int
    {
        return $this->profileIconId;
    }

    public function setProfileIconId(int $profileIconId): self
    {
        $this->profileIconId = $profileIconId;

        return $this;
    }

    public function getAccoundId(): ?string
    {
        return $this->accoundId;
    }

    public function setAccoundId(string $accoundId): self
    {
        $this->accoundId = $accoundId;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Rencontre>
     */
    public function getRencontres(): Collection
    {
        return $this->rencontres;
    }

    public function addRencontre(Rencontre $rencontre): self
    {
        if (!$this->rencontres->contains($rencontre)) {
            $this->rencontres[] = $rencontre;
            $rencontre->addInvocateur($this);
        }

        return $this;
    }

    public function removeRencontre(Rencontre $rencontre): self
    {
        if ($this->rencontres->removeElement($rencontre)) {
            $rencontre->removeInvocateur($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, HistoriqueLeague>
     */
    public function getHistoriqueLeagues(): Collection
    {
        return $this->historiqueLeagues;
    }

    public function addHistoriqueLeague(HistoriqueLeague $historiqueLeague): self
    {
        if (!$this->historiqueLeagues->contains($historiqueLeague)) {
            $this->historiqueLeagues[] = $historiqueLeague;
            $historiqueLeague->setInvocateur($this);
        }

        return $this;
    }

    public function removeHistoriqueLeague(HistoriqueLeague $historiqueLeague): self
    {
        if ($this->historiqueLeagues->removeElement($historiqueLeague)) {
            // set the owning side to null (unless already changed)
            if ($historiqueLeague->getInvocateur() === $this) {
                $historiqueLeague->setInvocateur(null);
            }
        }

        return $this;
    }
}
