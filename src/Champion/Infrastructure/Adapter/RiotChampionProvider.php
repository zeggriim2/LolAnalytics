<?php

declare(strict_types=1);

namespace App\Champion\Infrastructure\Adapter;

use App\Champion\Application\Dto\ChampionDto;
use App\Champion\Application\Port\RiotChampionProviderInterface;
use Zeggriim\RiotApiDataDragon\DataDragon\Endpoint\ChampionApiInterface;

final readonly class RiotChampionProvider implements RiotChampionProviderInterface
{
    public function __construct(
        private ChampionApiInterface $championApi,
    ) {
    }

    /**
     * @return ChampionDto[]
     */
    public function fetchAllChampions(string $version, string $locale = 'fr_FR'): array
    {
        $collection = $this->championApi->getChampionsAsCollection($version, $locale);

        $dtos = [];

        foreach ($collection->getChampions() as $champion) {
            $dtos[] = ChampionDto::fromBundleDto($champion, $version);
        }

        return $dtos;
    }

    public function fetchChampion(string $key, string $version, string $locale = 'fr_FR'): ChampionDto
    {
        $champion = $this->championApi->getChampionAsObject($key, $version, $locale);

        return ChampionDto::fromBundleDto($champion, $version);
    }
}
