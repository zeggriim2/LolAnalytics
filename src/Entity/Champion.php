<?php

namespace App\Entity;

use App\Repository\ChampionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChampionRepository::class)]
class Champion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 35)]
    private ?string $idLol = null;

    #[ORM\Column(type: Types::STRING, length: 10)]
    private ?string $key = null;

    #[ORM\Column(type: Types::STRING, length: 35)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 150)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $lore = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $blurb = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $partype = null;

    #[ORM\ManyToOne(inversedBy: 'champions')]
    private ?Version $version = null;

    #[ORM\ManyToMany(targetEntity: Skin::class, inversedBy: 'champions', cascade: ['persist'])]
    private Collection $skins;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $attack = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $defense = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $magic = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $difficulty = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'champions')]
    private ?Image $Image = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'champions')]
    private ?Passive $passive = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'champions')]
    private ?Stat $stat = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'champions', cascade: ['persist'])]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: Spell::class, inversedBy: 'champions', cascade: ['persist'])]
    private Collection $spells;

    public function __construct()
    {
        $this->skins = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->spells = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLore(): ?string
    {
        return $this->lore;
    }

    public function setLore(string $lore): self
    {
        $this->lore = $lore;

        return $this;
    }

    public function getBlurb(): ?string
    {
        return $this->blurb;
    }

    public function setBlurb(string $blurb): self
    {
        $this->blurb = $blurb;

        return $this;
    }

    public function getPartype(): ?string
    {
        return $this->partype;
    }

    public function setPartype(string $partype): self
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

    /**
     * @return Collection<int, skin>
     */
    public function getSkins(): Collection
    {
        return $this->skins;
    }

    public function addSkin(skin $skin): self
    {
        if (!$this->skins->contains($skin)) {
            $this->skins->add($skin);
        }

        return $this;
    }

    public function removeSkin(skin $skin): self
    {
        $this->skins->removeElement($skin);

        return $this;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(int $attack): self
    {
        $this->attack = $attack;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): self
    {
        $this->defense = $defense;

        return $this;
    }

    public function getMagic(): ?int
    {
        return $this->magic;
    }

    public function setMagic(int $magic): self
    {
        $this->magic = $magic;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->Image;
    }

    public function setImage(?Image $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getPassive(): ?Passive
    {
        return $this->passive;
    }

    public function setPassive(?Passive $passive): self
    {
        $this->passive = $passive;

        return $this;
    }

    public function getStat(): ?Stat
    {
        return $this->stat;
    }

    public function setStat(?Stat $stat): self
    {
        $this->stat = $stat;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

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
        }

        return $this;
    }

    public function removeSpell(Spell $spell): self
    {
        $this->spells->removeElement($spell);

        return $this;
    }
}
