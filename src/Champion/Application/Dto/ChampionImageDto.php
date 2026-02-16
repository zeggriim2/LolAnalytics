<?php

declare(strict_types=1);

namespace App\Champion\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Zeggriim\RiotApiDataDragon\DataDragon\Dto\Image;

final readonly class ChampionImageDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $full,
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $sprite,
        #[Assert\NotBlank]
        #[Assert\Length(max: 100)]
        public string $group,
        #[Assert\GreaterThanOrEqual(0)]
        public int $x,
        #[Assert\GreaterThanOrEqual(0)]
        public int $y,
        #[Assert\GreaterThanOrEqual(0)]
        public int $w,
        #[Assert\GreaterThanOrEqual(0)]
        public int $h,
    ) {
    }

    public static function fromBundleDto(Image $image): self
    {
        return new self(
            full: $image->full,
            sprite: $image->sprite,
            group: $image->group,
            x: $image->x,
            y: $image->y,
            w: $image->w,
            h: $image->h,
        );
    }
}
