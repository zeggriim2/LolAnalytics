<?php

namespace App\Services\API\LOL\LeagueOfLegends;

use App\Services\API\LOL\BaseApi;
use App\Services\API\LOL\DataDragon\Division;
use App\Services\API\LOL\DataDragon\Queue;
use App\Services\API\LOL\DataDragon\Tier;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueEntryDTO;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueListDTO;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use App\Services\API\LOL\LeagueOfLegends\Exception\LeagueArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class LeagueApi
{
    private const URL_LEAGUE_CHALLENGER_QUEUE =
        BaseApi::URL_RACINE_PLATFORM . 'league/v4/challengerleagues/by-queue/{queue}';
    private const URL_LEAGUE_GRANDMASTER_QUEUE =
        BaseApi::URL_RACINE_PLATFORM . 'league/v4/grandmasterleagues/by-queue/{queue}';
    private const URL_LEAGUE_MASTER_QUEUE =
        BaseApi::URL_RACINE_PLATFORM . 'league/v4/masterleagues/by-queue/{queue}';
    private const URL_LEAGUE_SUMMONERID =
        BaseApi::URL_RACINE_PLATFORM . 'league/v4/entries/by-summoner/{summonerId}';
    private const URL_LEAGUE_QUEUE_TIER_DIVISION =
        BaseApi::URL_RACINE_PLATFORM . 'league/v4/entries/{queue}/{tier}/{division}';
    private const URL_LEAGUE_LEAGUEID =
        BaseApi::URL_RACINE_PLATFORM . 'league/v4/leagues/{leagueId}';

    public function __construct(
        private BaseApi $baseApi,
        private DenormalizerInterface $denormalizer
    ) {
    }

    public function leagueBySummonerId(
        string $summonerId
    ) {
        if ('' === $summonerId) {
            throw new ForbiddenException('Summoner ID est vide');
        }

        $url = $this->baseApi->constructUrl(
            self::URL_LEAGUE_SUMMONERID,
            [
                'platform' => $this->baseApi->platform,
                'summonerId' => $summonerId,
            ]
        );

        $leagueSummonerId = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $leagueSummonerId ? $this->denormalizeArray($leagueSummonerId) : null;
    }

    public function leagueChallengerByQueue(
        string $queue
    ) {
        if ('' === $queue) {
            throw new ForbiddenException('Queue vide.');
        }

        if (!\in_array($queue, Queue::ALL_QEUEUES, true)) {
            throw new LeagueArgumentException("N'existe pas dans l'éléments Queue");
        }

        $url = $this->baseApi->constructUrl(
            self::URL_LEAGUE_CHALLENGER_QUEUE,
            [
                'platform' => $this->baseApi->platform,
                'queue' => $queue,
            ]
        );

        $leagueChallenger = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $leagueChallenger ? $this->denormalize($leagueChallenger) : null;
    }

    public function leagueGrandMasterByQueue(
        string $queue
    ) {
        if ('' === $queue) {
            throw new ForbiddenException('Queue vide.');
        }

        if (!\in_array($queue, Queue::ALL_QEUEUES, true)) {
            throw new LeagueArgumentException("N'existe pas dans l'éléments Queue");
        }

        $url = $this->baseApi->constructUrl(
            self::URL_LEAGUE_GRANDMASTER_QUEUE,
            [
                'platform' => $this->baseApi->platform,
                'queue' => $queue,
            ]
        );

        $leagueGrandMaster = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $leagueGrandMaster ? $this->denormalize($leagueGrandMaster) : null;
    }

    public function leagueMasterByQueue(
        string $queue
    ) {
        if ('' === $queue) {
            throw new ForbiddenException('Queue vide.');
        }

        if (!\in_array($queue, Queue::ALL_QEUEUES, true)) {
            throw new LeagueArgumentException("N'existe pas dans l'éléments Queue");
        }

        $url = $this->baseApi->constructUrl(
            self::URL_LEAGUE_MASTER_QUEUE,
            [
                'platform' => $this->baseApi->platform,
                'queue' => $queue,
            ]
        );

        $leagueMaster = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $leagueMaster ? $this->denormalize($leagueMaster) : null;
    }

    public function leagueByLeagueId(
        string $leagueId
    ) {
        if ('' === $leagueId) {
            throw new ForbiddenException('League ID est vide.');
        }

        $url = $this->baseApi->constructUrl(
            self::URL_LEAGUE_LEAGUEID,
            [
                'platform' => $this->baseApi->platform,
                'leagueId' => $leagueId,
            ]
        );

        $league = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $league ? $this->denormalize($league) : null;
    }

    public function leagueByQueueByTierByDivision(
        string $queue,
        string $tier,
        string $division
    ) {
        if ('' === $queue || '' === $tier || '' === $division) {
            throw new ForbiddenException('Queue/Tier/Division est vide');
        }

        if (
            !\in_array($queue, Queue::ALL_QEUEUES, true) ||
            !\in_array($tier, Tier::ALL_TIERS, true) ||
            !\in_array($division, Division::ALL_DIVISION, true)
        ) {
            throw new LeagueArgumentException("N'existe pas dans les différents éléments Queue/Tier/Division");
        }
        $url = $this->baseApi->constructUrl(self::URL_LEAGUE_QUEUE_TIER_DIVISION, [
            'platform' => $this->baseApi->platform,
            'queue' => $queue,
            'tier' => $tier,
            'division' => $division,
        ]);

        $league = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $league ? $this->denormalizeArray($league) : null;
    }

    private function denormalizeArray(
        array $datas
    ): array {
        $listEntity = [];
        foreach ($datas as $key => $data) {
            $listEntity[] = $this->denormalizer->denormalize($data, LeagueEntryDTO::class);
        }

        return $listEntity;
    }

    private function denormalize(
        array $data
    ) {
        return $this->denormalizer->denormalize($data, LeagueListDTO::class);
    }
}
