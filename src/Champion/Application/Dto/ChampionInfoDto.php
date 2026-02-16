<?php

declare(strict_types=1);

namespace App\Champion\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Zeggriim\RiotApiDataDragon\DataDragon\Dto\Champion\ChampionInfo as BundleChampionInfo;

final readonly class ChampionInfoDto
{
    public function __construct(
        #[Assert\Range(min: 0, max: 10)]
        public int $attack,
        #[Assert\Range(min: 0, max: 10)]
        public int $defense,
        #[Assert\Range(min: 0, max: 10)]
        public int $magic,
        #[Assert\Range(min: 0, max: 10)]
        public int $difficulty,
    ) {
    }

    public static function fromBundleDto(BundleChampionInfo $info): self
    {
        return new self(
            attack: $info->attack,
            defense: $info->defense,
            magic: $info->magic,
            difficulty: $info->difficulty,
        );
    }
}
