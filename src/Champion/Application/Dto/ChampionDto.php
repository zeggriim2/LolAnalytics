<?php

declare(strict_types=1);

namespace App\Champion\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Zeggriim\RiotApiDataDragon\DataDragon\Dto\Champion\Champion as BundleChampion;

final readonly class ChampionDto
{
    /**
     * @param string[] $tags
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 100)]
        public string $riotId,
        #[Assert\NotBlank]
        #[Assert\Length(max: 20)]
        public string $version,
        #[Assert\NotBlank]
        #[Assert\Length(max: 10)]
        public string $championKey,
        #[Assert\NotBlank]
        #[Assert\Length(max: 100)]
        public string $name,
        #[Assert\Length(max: 255)]
        public string $title,
        public string $blurb,
        #[Assert\Length(max: 100)]
        public string $partype,
        #[Assert\All([
            new Assert\NotBlank(),
            new Assert\Length(max: 50),
        ])]
        public array $tags,
        #[Assert\Valid]
        public ChampionImageDto $image,
        #[Assert\Valid]
        public ChampionInfoDto $info,
        #[Assert\Valid]
        public ChampionStatsDto $stats,
    ) {
    }

    public static function fromBundleDto(BundleChampion $champion, string $version): self
    {
        if (null === $champion->info) {
            throw new \InvalidArgumentException(sprintf('Champion "%s" has no info data', $champion->id ?? 'unknown'));
        }

        if (null === $champion->stats) {
            throw new \InvalidArgumentException(sprintf('Champion "%s" has no stats data', $champion->id ?? 'unknown'));
        }

        if (null === $champion->image) {
            throw new \InvalidArgumentException(sprintf('Champion "%s" has no image data', $champion->id ?? 'unknown'));
        }

        return new self(
            riotId: $champion->id ?? '',
            version: $version,
            championKey: $champion->key ?? '',
            name: $champion->name ?? '',
            title: $champion->title ?? '',
            blurb: $champion->blurb ?? '',
            partype: $champion->partype ?? '',
            tags: $champion->tags,
            image: ChampionImageDto::fromBundleDto($champion->image),
            info: ChampionInfoDto::fromBundleDto($champion->info),
            stats: ChampionStatsDto::fromBundleDto($champion->stats),
        );
    }
}
