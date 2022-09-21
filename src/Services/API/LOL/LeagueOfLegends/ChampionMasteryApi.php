<?php

namespace App\Services\API\LOL\LeagueOfLegends;

use App\Services\API\LOL\BaseApi;
use App\Services\API\LOL\LeagueOfLegends\Denormalize\DenormalizerApi;
use App\Services\API\LOL\LeagueOfLegends\DTO\ChampionMastery\ChampionMasteryDto;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use Symfony\Component\HttpFoundation\Request;

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
        private DenormalizerApi $denormalizerApi
    ) {
    }

    /**
     * @throws ForbiddenException
     *
     * @return ChampionMasteryDto[]|null
     */
    public function getChampionMasteryBySummonerId(
        string $summonerId
    ): ?array {
        if ('' === $summonerId) {
            throw new ForbiddenException('Summoner ID est vide');
        }

        $url = $this->baseApi->constructUrl(
            self::URL_CHAMPION_MASTERY_SUMMONERID,
            [
                'platform' => $this->baseApi->platform,
                'summonerId' => $summonerId,
            ]
        );

        $championMasterySummonerId = $this->baseApi->callApiArray(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $championMasterySummonerId ?
            $this->denormalizerApi->denormalizeArray($championMasterySummonerId, ChampionMasteryDto::class) :
            null;
    }

    /**
     * @throws ForbiddenException
     */
    public function getChampionMasteryBySummonerIdAndByChampionId(
        string $summonerId,
        string $championId
    ): ?ChampionMasteryDto {
        if ('' === $summonerId) {
            throw new ForbiddenException('Summoner ID est vide');
        }

        if ('' === $championId) {
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

        $championMasterySummonerIdChampionId = $this->baseApi->callApiArray(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $championMasterySummonerIdChampionId ?
            $this->denormalizerApi->denormalize($championMasterySummonerIdChampionId, ChampionMasteryDto::class) :
            null;
    }

    /**
     * @throws ForbiddenException
     */
    public function getChampionMasteryScoreBySummonerId(
        string $summonerId
    ): int {
        if ('' === $summonerId) {
            throw new ForbiddenException('Summoner ID est vide');
        }

        $url = $this->baseApi->constructUrl(
            self::URL_CHAMPION_MASTERY_SCORE_SUMMONERID,
            [
                'platform' => $this->baseApi->platform,
                'summonerId' => $summonerId,
            ]
        );

        return (int) $this->baseApi->callApi(
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
}
