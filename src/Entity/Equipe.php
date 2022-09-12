<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    private ?string $shortName = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'equipe1', targetEntity: Versus::class)]
    private Collection $versusEquipe1;

    #[ORM\OneToMany(mappedBy: 'equipe2', targetEntity: Versus::class)]
    private Collection $versusEquipe2;

    #[ORM\ManyToMany(targetEntity: Competition::class, mappedBy: 'equipes')]
    private Collection $competitions;

    public function __construct()
    {
        $this->versusEquipe1 = new ArrayCollection();
        $this->versusEquipe2 = new ArrayCollection();
        $this->competitions = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

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

    /**
     * @return Collection<int, Versus>
     */
    public function getVersusEquipe1(): Collection
    {
        return $this->versusEquipe1;
    }

    public function addVersusEquipe1(Versus $versusEquipe1): self
    {
        if (!$this->versusEquipe1->contains($versusEquipe1)) {
            $this->versusEquipe1->add($versusEquipe1);
            $versusEquipe1->setEquipe1($this);
        }

        return $this;
    }

    public function removeVersusEquipe1(Versus $versusEquipe1): self
    {
        if ($this->versusEquipe1->removeElement($versusEquipe1)) {
            // set the owning side to null (unless already changed)
            if ($versusEquipe1->getEquipe1() === $this) {
                $versusEquipe1->setEquipe1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Versus>
     */
    public function getVersusEquipe2(): Collection
    {
        return $this->versusEquipe2;
    }

    public function addVersusEquipe2(Versus $versusEquipe2): self
    {
        if (!$this->versusEquipe2->contains($versusEquipe2)) {
            $this->versusEquipe2->add($versusEquipe2);
            $versusEquipe2->setEquipe2($this);
        }

        return $this;
    }

    public function removeVersusEquipe2(Versus $versusEquipe2): self
    {
        if ($this->versusEquipe2->removeElement($versusEquipe2)) {
            // set the owning side to null (unless already changed)
            if ($versusEquipe2->getEquipe2() === $this) {
                $versusEquipe2->setEquipe2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Competition>
     */
    public function getCompetitions(): Collection
    {
        return $this->competitions;
    }

    public function addCompetition(Competition $competition): self
    {
        if (!$this->competitions->contains($competition)) {
            $this->competitions->add($competition);
            $competition->addEquipe($this);
        }

        return $this;
    }

    public function removeCompetition(Competition $competition): self
    {
        if ($this->competitions->removeElement($competition)) {
            $competition->removeEquipe($this);
        }

        return $this;
    }
}
