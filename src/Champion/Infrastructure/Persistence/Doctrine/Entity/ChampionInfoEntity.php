<?php

declare(strict_types=1);

namespace App\Champion\Infrastructure\Persistence\Doctrine\Entity;

use App\Champion\Domain\Model\ChampionInfo;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'champion_info')]
class ChampionInfoEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'info')]
    #[ORM\JoinColumn(name: 'riot_id', referencedColumnName: 'riot_id')]
    #[ORM\JoinColumn(name: 'version', referencedColumnName: 'version')]
    private ChampionEntity $champion;

    #[ORM\Column(type: Types::INTEGER)]
    private int $attack;

    #[ORM\Column(type: Types::INTEGER)]
    private int $defense;

    #[ORM\Column(type: Types::INTEGER)]
    private int $magic;

    #[ORM\Column(type: Types::INTEGER)]
    private int $difficulty;

    public static function fromDomain(ChampionInfo $info, ChampionEntity $champion): self
    {
        $e = new self();
        $e->champion = $champion;
        $e->attack = $info->attack();
        $e->defense = $info->defense();
        $e->magic = $info->magic();
        $e->difficulty = $info->difficulty();

        return $e;
    }

    public function toDomain(): ChampionInfo
    {
        return new ChampionInfo(
            attack: $this->attack,
            defense: $this->defense,
            magic: $this->magic,
            difficulty: $this->difficulty,
        );
    }
}
