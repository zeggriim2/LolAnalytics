<?php

namespace App\Services\API\LOL\LeagueOfLegends\Denormalize;

use App\Services\API\LOL\LeagueOfLegends\DTO\Champion\ChampionInfoDTO;
use App\Services\API\LOL\LeagueOfLegends\DTO\ChampionMastery\ChampionMasteryDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueEntryDTO;
use App\Services\API\LOL\LeagueOfLegends\LeagueApi;
use phpDocumentor\Reflection\Types\ArrayKey;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DenormalizerApi
{
    public function __construct(
        private DenormalizerInterface $denormalizer
    )
    {
    }

    /**
     * @param array{array-key, array{string, string}} $datas
     * @return array<array-key,LeagueEntryDTO|ChampionMasteryDto>
     */
    public function denormalizeArray(array $datas, string $type): array
    {
        $listEntity = [];
        foreach ($datas as $key => $data) {
            $listEntity[] = $this->denormalizer->denormalize($data, $type);
        }

        return $listEntity;
    }

    /**
     * @param array<array-key, mixed> $datas
     * @param string $type
     * @return mixed
     */
    public function denormalize(
        array  $datas,
        string $type
    )
    {
        return $this->denormalizer->denormalize($datas, $type);
    }
}