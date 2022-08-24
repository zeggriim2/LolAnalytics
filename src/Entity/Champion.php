<?php

namespace App\Entity;

use App\Repository\ChampionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ChampionRepository::class)]
#[ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")]
class Champion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $idName;

    #[ORM\Column(type: 'string', length: 5)]
    private $key;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 50, nullable: true, options: ['default'=> null])]
    private $partype;

    #[ORM\ManyToOne(targetEntity: Version::class, inversedBy: 'champions')]
    private $version;

    #[ORM\OneToOne(mappedBy: 'champion', targetEntity: InfoChampion::class, cascade: ['persist', 'remove'])]
    private $infoChampion;

    #[ORM\ManyToOne(targetEntity: Image::class, inversedBy: 'champions')]
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdName(): ?string
    {
        return $this->idName;
    }

    public function setIdName(string $idName): self
    {
        $this->idName = $idName;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPartype(): ?string
    {
        return $this->partype;
    }

    public function setPartype(?string $partype): self
    {
        $this->partype = $partype;

        return $this;
    }

    public function getVersion(): ?Version
    {
        return $this->version;
    }

    public function setVersion(?Version $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getInfoChampion(): ?InfoChampion
    {
        return $this->infoChampion;
    }

    public function setInfoChampion(?InfoChampion $infoChampion): self
    {
        // unset the owning side of the relation if necessary
        if ($infoChampion === null && $this->infoChampion !== null) {
            $this->infoChampion->setChampion(null);
        }

        // set the owning side of the relation if necessary
        if ($infoChampion !== null && $infoChampion->getChampion() !== $this) {
            $infoChampion->setChampion($this);
        }

        $this->infoChampion = $infoChampion;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }
}
