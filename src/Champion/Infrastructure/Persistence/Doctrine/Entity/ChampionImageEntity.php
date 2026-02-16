<?php

declare(strict_types=1);

namespace App\Champion\Infrastructure\Persistence\Doctrine\Entity;

use App\Champion\Domain\Model\ChampionImage;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'champion_images')]
class ChampionImageEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'image')]
    #[ORM\JoinColumn(name: 'riot_id', referencedColumnName: 'riot_id')]
    #[ORM\JoinColumn(name: 'version', referencedColumnName: 'version')]
    private ChampionEntity $champion;

    #[ORM\Column(name: '`full`', type: Types::STRING, length: 255)]
    private string $full;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $sprite;

    #[ORM\Column(name: '`group`', type: Types::STRING, length: 100)]
    private string $group;

    #[ORM\Column(type: Types::INTEGER)]
    private int $x;

    #[ORM\Column(type: Types::INTEGER)]
    private int $y;

    #[ORM\Column(type: Types::INTEGER)]
    private int $w;

    #[ORM\Column(type: Types::INTEGER)]
    private int $h;

    public static function fromDomain(ChampionImage $image, ChampionEntity $champion): self
    {
        $e = new self();
        $e->champion = $champion;
        $e->full = $image->full();
        $e->sprite = $image->sprite();
        $e->group = $image->group();
        $e->x = $image->x();
        $e->y = $image->y();
        $e->w = $image->w();
        $e->h = $image->h();

        return $e;
    }

    public function toDomain(): ChampionImage
    {
        return new ChampionImage(
            full: $this->full,
            sprite: $this->sprite,
            group: $this->group,
            x: $this->x,
            y: $this->y,
            w: $this->w,
            h: $this->h,
        );
    }
}
