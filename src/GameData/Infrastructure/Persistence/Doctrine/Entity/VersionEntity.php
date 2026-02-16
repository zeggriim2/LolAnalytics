<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Entity;

use App\Champion\Infrastructure\Persistence\Doctrine\Entity\ChampionEntity;
use App\GameData\Domain\Model\Version;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'game_data_versions')]
class VersionEntity
{
    #[ORM\Id()]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $version;

    /**
     * @var Collection<int, ChampionEntity>
     */
    #[ORM\OneToMany(
        targetEntity: ChampionEntity::class,
        mappedBy: 'gameMode',
        cascade: ['persist']
    )]
    public Collection $champions;

    public function __construct()
    {
        $this->champions = new ArrayCollection();
    }

    public static function fromDomain(Version $version): self
    {
        $entity = new self();
        $entity->version = $version->version();

        return $entity;
    }

    public function toDomain(): Version
    {
        return new Version(
            $this->version
        );
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }
}
