<?php

namespace App\Services\API\LOL\LeagueOfLegends;

use App\Services\API\LOL\BaseApi;
use App\Services\API\LOL\LeagueOfLegends\DTO\SummonerDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class SummonerApi
{
    private const URL_NAME =
        BaseApi::URL_RACINE_PLATFORM . 'summoner/v4/summoners/by-name/{name}';
    private const URL_ACCOUNTID =
        BaseApi::URL_RACINE_PLATFORM . 'summoner/v4/summoners/by-account/{accountId}';
    private const URL_PUUID =
        BaseApi::URL_RACINE_PLATFORM . 'summoner/v4/summoners/by-puuid/{puuid}';
    private const URL_SUMMONER_ID =
        BaseApi::URL_RACINE_PLATFORM . 'summoner/v4/summoners/{summonerId}';

    public function __construct(
        private BaseApi $baseApi,
        private DenormalizerInterface $denormalizer
    ) {
    }

    public function summonerBySummonerName(string $summonerName): ?SummonerDTO
    {
        $url = $this->baseApi->constructUrl(self::URL_NAME, [
            'platform' => $this->baseApi->platform,
            'name' => $summonerName,
        ]);
        /** @var array<string,int|string>|null $summoner */
        $summoner = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );
        if (null === $summoner) {
            return null;
        }

        return $this->denormalize($summoner);
    }

    public function summonerByAccountId(string $accountId): ?SummonerDTO
    {
        $url = $this->baseApi->constructUrl(self::URL_ACCOUNTID, [
            'platform' => $this->baseApi->platform,
            'accountId' => $accountId,
        ]);
        /** @var array<string,int|string>|null $summoner */
        $summoner = $summoner = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        if (null === $summoner) {
            return null;
        }

        return $this->denormalize($summoner);
    }

    public function summonerByPuuid(string $puuid): ?SummonerDTO
    {
        $url = $this->baseApi->constructUrl(self::URL_PUUID, [
            'platform' => $this->baseApi->platform,
            'puuid' => $puuid,
        ]);

        /** @var array<string,int|string>|null $summoner */
        $summoner = $summoner = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        if (null === $summoner) {
            return null;
        }

        return $this->denormalize($summoner);
    }

    public function summonerBySummonerId(string $summonerId): ?SummonerDTO
    {
        $url = $this->baseApi->constructUrl(self::URL_SUMMONER_ID, [
            'platform' => $this->baseApi->platform,
            'summonerId' => $summonerId,
        ]);

        /** @var array<string,int|string>|null $summoner */
        $summoner = $summoner = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        if (null === $summoner) {
            return null;
        }

        return $this->denormalize($summoner);
    }

    /**
     * @param array<string,int|string> $data
     */
    private function denormalize(array $data): SummonerDTO
    {
        return $this->denormalizer->denormalize($data, SummonerDTO::class);
    }
}
