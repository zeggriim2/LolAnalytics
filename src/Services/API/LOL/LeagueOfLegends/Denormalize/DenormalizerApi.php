<?php

namespace App\Services\API\LOL\LeagueOfLegends\Denormalize;

use App\Services\API\LOL\LeagueOfLegends\DTO\Champion\ChampionInfoDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DenormalizerApi
{
    public function __construct(
        private DenormalizerInterface $denormalizer
    )
    {
    }

    /**
     * @param array $datas
     * @return array
     */
    public function denormalizeArray(array $datas, string $type) {
        $listEntity = [];
        foreach ($datas as $key => $data) {
            $listEntity[] = $this->denormalizer->denormalize($data, $type);
        }

        return $listEntity;
    }

    /**
     * @param mixed[] $datas
     * @param string $type
     *
     */
    public function denormalize(
        array  $datas,
        string $type
    ) {
        return $this->denormalizer->denormalize($datas, $type);
    }
}