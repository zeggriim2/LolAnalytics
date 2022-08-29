<?php

namespace App\Services\API\LOL\LeagueOfLegends;

use App\Services\API\LOL\BaseApi;
use App\Services\API\LOL\LeagueOfLegends\DTO\Champion\ChampionInfoDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ChampionApi
{
    private const URL_CHAMPION_ROTATION =
        BaseApi::URL_RACINE_PLATFORM . 'platform/v3/champion-rotations';

    public function __construct(
        private BaseApi $baseApi,
        private DenormalizerInterface $denormalizer
    ) {
    }

    public function getChampionRotation(): ?ChampionInfoDTO
    {
        $url = $this->baseApi->constructUrl(
            self::URL_CHAMPION_ROTATION,
            [
                'platform' => $this->baseApi->platform,
            ]
        );

        $championRotation = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $championRotation ? $this->denormalize($championRotation) : null;
    }

    /**
     * @param mixed[] $data
     * @return ChampionInfoDTO
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    private function denormalize(
        array $data
    ): ChampionInfoDTO {
        return $this->denormalizer->denormalize($data, ChampionInfoDTO::class);
    }
}
