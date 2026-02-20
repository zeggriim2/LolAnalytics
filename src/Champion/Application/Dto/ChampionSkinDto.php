<?php

declare(strict_types=1);

namespace App\Champion\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Zeggriim\RiotApiDataDragon\DataDragon\Dto\Champion\ChampionSkin;

final class ChampionSkinDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $id,
        #[Assert\NotBlank]
        public int $num,
        #[Assert\NotBlank]
        public string $name,
        public bool $chromas = false
    ) {
    }

    public static function fromBundleDto(ChampionSkin $skin): self
    {
        return new self(
            id: $skin->id,
            num: $skin->num,
            name: $skin->name,
            chromas: $skin->chromas
        );
    }
}
