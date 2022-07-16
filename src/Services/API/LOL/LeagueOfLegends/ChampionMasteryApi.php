<?php

namespace App\Services\API\LOL\LeagueOfLegends;

use App\Services\API\LOL\BaseApi;
use App\Services\API\LOL\LeagueOfLegends\DTO\ChampionMastery\ChampionMasteryDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueEntryDTO;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ChampionMasteryApi
{

    private const URL_CHAMPION_MASTERY_SUMMONERID =
        BaseApi::URL_RACINE_PLATFORM . 'champion-mastery/v4/champion-masteries/by-summoner/{summonerId}';
    private const URL_CHAMPION_MASTERY_SUMMONERID_CHAMPIONID =
        BaseApi::URL_RACINE_PLATFORM . 'champion-mastery/v4/champion-masteries/by-summoner/{summonerId}/by-champion/{championId}';
    private const URL_CHAMPION_MASTERY_SCORE_SUMMONERID =
        BaseApi::URL_RACINE_PLATFORM . 'champion-mastery/v4/scores/by-summoner/{summonerId}';


    public function __construct(
        private BaseApi $baseApi,
        private DenormalizerInterface $denormalizer
    ) {
    }

    /**
     * @param string $summonerId
     * @return ChampionMasteryDto[]|null
     * @throws ForbiddenException
     */
    public function getChampionMasteryBySummonerId(
        string $summonerId
    ): ?array
    {
        if (strlen($summonerId) === 0) {
            throw new ForbiddenException('Summoner ID est vide');
        }

        $url = $this->baseApi->constructUrl(
            self::URL_CHAMPION_MASTERY_SUMMONERID,
            [
                'platform' => $this->baseApi->platform,
                'summonerId' => $summonerId,
            ]
        );

        $championMasterySummonerId = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $championMasterySummonerId ? $this->denormalizeArray($championMasterySummonerId) : null;
    }

    /**
     * @param string $summonerId
     * @param string $championId
     * @return ChampionMasteryDto|null
     * @throws ForbiddenException
     */
    public function getChampionMasteryBySummonerIdAndByChampionId(
        string $summonerId,
        string $championId
    ): ?ChampionMasteryDto
    {
        if (strlen($summonerId) === 0) {
            throw new ForbiddenException('Summoner ID est vide');
        }

        if (strlen($championId) === 0) {
            throw new ForbiddenException('Champion ID est vide');
        }

        $url = $this->baseApi->constructUrl(
            self::URL_CHAMPION_MASTERY_SUMMONERID_CHAMPIONID,
            [
                'platform' => $this->baseApi->platform,
                'summonerId' => $summonerId,
                'championId' => $championId,
            ]
        );

        $championMasterySummonerIdChampionId = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $championMasterySummonerIdChampionId ? $this->denormalize($championMasterySummonerIdChampionId) : null;
    }

    /**
     * @param string $summonerId
     * @throws ForbiddenException
     */
    public function getChampionMasteryScoreBySummonerId(
        string $summonerId
    )
    {
        if(strlen($summonerId) === 0) {
            throw new ForbiddenException('Summoner ID est vide');
        }

        $url = $this->baseApi->constructUrl(
            self::URL_CHAMPION_MASTERY_SCORE_SUMMONERID,
            [
                'platform' => $this->baseApi->platform,
                'summonerId' => $summonerId,
            ]
        );

        return (int)$this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ],
            'content'
        );
    }


    /**
     * @param array $datas
     * @return ChampionMasteryDto[]
     */
    private function denormalizeArray(
        array $datas
    ): array {
        $listEntity = [];
        foreach ($datas as $key => $data) {
            $listEntity[] = $this->denormalizer->denormalize($data, ChampionMasteryDto::class);
        }

        return $listEntity;
    }

    /**
     * @param array $data
     * @return ChampionMasteryDto
     */
    private function denormalize(
        array $data
    ): ChampionMasteryDto {
        return $this->denormalizer->denormalize($data, ChampionMasteryDto::class);
    }
}