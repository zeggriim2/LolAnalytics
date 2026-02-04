<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Entity;

use App\GameData\Domain\Model\Season;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'game_data_seasons')]
class SeasonEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 50)]
    private string $season;

    public static function fromDomain(Season $season): self
    {
        $entity = new self();
        $entity->id = $season->id();
        $entity->season = $season->season();

        return $entity;
    }

    public function toDomain(): Season
    {
        return new Season(
            $this->id,
            $this->season,
        );
    }

    public function getId(): int
    {
        return $this->id;
    }
}
