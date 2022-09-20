<?php

namespace App\Services\API\LOL\LeagueOfLegends;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DenormalizeArrayApi
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
}