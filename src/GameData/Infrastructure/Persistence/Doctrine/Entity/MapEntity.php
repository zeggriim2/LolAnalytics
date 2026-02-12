<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Entity;

use App\GameData\Domain\Model\Map;
use App\Match\Infrastructure\Persistence\Doctrine\Entity\MatchEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'game_data_maps')]
class MapEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    private int $mapId;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $mapName;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes;

    /**
     * @var Collection<int, MatchEntity>
     */
    #[ORM\OneToMany(
        targetEntity: MatchEntity::class,
        mappedBy: 'map',
        cascade: ['persist']
    )]
    public Collection $matchs;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
    }

    public static function fromDomain(Map $map): self
    {
        $entity = new self();
        $entity->mapId = $map->mapId();
        $entity->mapName = $map->mapName();
        $entity->notes = $map->notes();

        return $entity;
    }

    public function toDomain(): Map
    {
        return new Map(
            $this->mapId,
            $this->mapName,
            $this->notes,
        );
    }

    public function getMapId(): int
    {
        return $this->mapId;
    }

    public function setMapId(int $mapId): void
    {
        $this->mapId = $mapId;
    }

    public function getMapName(): string
    {
        return $this->mapName;
    }

    public function setMapName(string $mapName): void
    {
        $this->mapName = $mapName;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }
}
