<?php

declare(strict_types=1);

namespace App\Champion\Infrastructure\Persistence\Doctrine\Entity;

use App\Champion\Domain\Model\ChampionSkin;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'champion_skins')]
class ChampionSkinEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ChampionEntity::class, inversedBy: 'skins')]
    #[ORM\JoinColumn(name: 'riot_id', referencedColumnName: 'riot_id')]
    #[ORM\JoinColumn(name: 'version', referencedColumnName: 'version')]
    private ChampionEntity $champion;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $skinId;

    #[ORM\Column(type: Types::INTEGER)]
    private int $num;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $chromas;

    public static function fromDomain(ChampionSkin $skin, ChampionEntity $champion): self
    {
        $e = new self();
        $e->champion = $champion;
        $e->skinId = $skin->skinId();
        $e->num = $skin->num();
        $e->name = $skin->name();
        $e->chromas = $skin->chromas();

        return $e;
    }

    public function toDomain(): ChampionSkin
    {
        return new ChampionSkin(
            skinId: $this->skinId,
            num: $this->num,
            name: $this->name,
            chromas: $this->chromas,
        );
    }
}
