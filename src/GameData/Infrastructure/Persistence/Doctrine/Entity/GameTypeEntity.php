<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Entity;

use App\GameData\Domain\Model\GameType;
use App\Match\Infrastructure\Persistance\Doctrine\Entity\MatchEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'game_data_game_types')]
class GameTypeEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, length: 50)]
    private string $gameType;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $description;

    /**
     * @var Collection<int, MatchEntity>
     */
    #[ORM\OneToMany(
        targetEntity: MatchEntity::class,
        mappedBy: 'gameType',
        cascade: ['persist']
    )]
    public Collection $matchs;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
    }

    public static function fromDomain(GameType $gameType): self
    {
        $entity = new self();
        $entity->gameType = $gameType->gameType();
        $entity->description = $gameType->description();

        return $entity;
    }

    public function toDomain(): GameType
    {
        return new GameType(
            $this->gameType,
            $this->description,
        );
    }

    public function getGameType(): string
    {
        return $this->gameType;
    }

    public function setGameType(string $gameType): void
    {
        $this->gameType = $gameType;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
