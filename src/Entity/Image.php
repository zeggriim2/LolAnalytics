<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $fullname = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $sprite = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $groupe = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $x = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $y = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $w = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $h = null;

    #[ORM\OneToMany(mappedBy: 'Image', targetEntity: Champion::class)]
    private Collection $champions;

    #[ORM\OneToMany(mappedBy: 'image', targetEntity: Passive::class)]
    private Collection $passives;

    #[ORM\OneToMany(mappedBy: 'image', targetEntity: Spell::class)]
    private Collection $spells;

    public function __construct()
    {
        $this->champions = new ArrayCollection();
        $this->passives = new ArrayCollection();
        $this->spells = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getSprite(): ?string
    {
        return $this->sprite;
    }

    public function setSprite(string $sprite): self
    {
        $this->sprite = $sprite;

        return $this;
    }

    public function getGroupe(): ?string
    {
        return $this->groupe;
    }

    public function setGroupe(string $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(int $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(int $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getW(): ?int
    {
        return $this->w;
    }

    public function setW(int $w): self
    {
        $this->w = $w;

        return $this;
    }

    public function getH(): ?int
    {
        return $this->h;
    }

    public function setH(int $h): self
    {
        $this->h = $h;

        return $this;
    }

    /**
     * @return Collection<int, Champion>
     */
    public function getChampions(): Collection
    {
        return $this->champions;
    }

    public function addChampion(Champion $champion): self
    {
        if (!$this->champions->contains($champion)) {
            $this->champions->add($champion);
            $champion->setImage($this);
        }

        return $this;
    }

    public function removeChampion(Champion $champion): self
    {
        if ($this->champions->removeElement($champion)) {
            // set the owning side to null (unless already changed)
            if ($champion->getImage() === $this) {
                $champion->setImage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Passive>
     */
    public function getPassives(): Collection
    {
        return $this->passives;
    }

    public function addPassife(Passive $passife): self
    {
        if (!$this->passives->contains($passife)) {
            $this->passives->add($passife);
            $passife->setImage($this);
        }

        return $this;
    }

    public function removePassife(Passive $passife): self
    {
        if ($this->passives->removeElement($passife)) {
            // set the owning side to null (unless already changed)
            if ($passife->getImage() === $this) {
                $passife->setImage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Spell>
     */
    public function getSpells(): Collection
    {
        return $this->spells;
    }

    public function addSpell(Spell $spell): self
    {
        if (!$this->spells->contains($spell)) {
            $this->spells->add($spell);
            $spell->setImage($this);
        }

        return $this;
    }

    public function removeSpell(Spell $spell): self
    {
        if ($this->spells->removeElement($spell)) {
            // set the owning side to null (unless already changed)
            if ($spell->getImage() === $this) {
                $spell->setImage(null);
            }
        }

        return $this;
    }
}
