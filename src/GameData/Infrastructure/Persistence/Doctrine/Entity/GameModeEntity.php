<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Entity;

use App\GameData\Domain\Model\GameMode;
use App\Match\Infrastructure\Persistence\Doctrine\Entity\MatchEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'game_data_game_modes')]
class GameModeEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, length: 50)]
    private string $gameMode;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $description;

    /**
     * @var Collection<int, MatchEntity>
     */
    #[ORM\OneToMany(
        targetEntity: MatchEntity::class,
        mappedBy: 'gameMode',
        cascade: ['persist']
    )]
    public Collection $matchs;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
    }

    public static function fromDomain(GameMode $gameMode): self
    {
        $entity = new self();
        $entity->gameMode = $gameMode->gameMode();
        $entity->description = $gameMode->description();

        return $entity;
    }

    public function toDomain(): GameMode
    {
        return new GameMode(
            $this->gameMode,
            $this->description,
        );
    }

    public function getGameMode(): string
    {
        return $this->gameMode;
    }

    public function setGameMode(string $gameMode): void
    {
        $this->gameMode = $gameMode;
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
