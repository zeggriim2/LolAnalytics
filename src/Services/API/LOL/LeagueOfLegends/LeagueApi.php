<?php

namespace App\Services\API\LOL\LeagueOfLegends;

use App\Services\API\LOL\BaseApi;
use App\Services\API\LOL\DataDragon\Division;
use App\Services\API\LOL\DataDragon\Queue;
use App\Services\API\LOL\DataDragon\Tier;
use App\Services\API\LOL\LeagueOfLegends\Denormalize\DenormalizerApi;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueEntryDTO;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueListDTO;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use App\Services\API\LOL\LeagueOfLegends\Exception\LeagueArgumentException;
use Symfony\Component\HttpFoundation\Request;

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
        private DenormalizerApi $denormalizerApi
    ) {
    }

    /**
     * @throws ForbiddenException
     *
     * @return LeagueEntryDTO[]|null
     */
    public function leagueBySummonerId(
        string $summonerId
    ): ?array {
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

        $leagueSummonerId = $this->baseApi->callApiArray(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $leagueSummonerId ?
            $this->denormalizerApi->denormalizeArray($leagueSummonerId, LeagueEntryDTO::class) : null;
    }

    /**
     * @throws ForbiddenException
     * @throws LeagueArgumentException
     */
    public function leagueChallengerByQueue(
        string $queue
    ): ?LeagueListDTO {
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

        $leagueChallenger = $this->baseApi->callApiArray(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $leagueChallenger ?
            $this->denormalizerApi->denormalize($leagueChallenger, LeagueListDTO::class) :
            null;
    }

    /**
     * @throws ForbiddenException
     * @throws LeagueArgumentException
     */
    public function leagueGrandMasterByQueue(
        string $queue
    ): ?LeagueListDTO {
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

        $leagueGrandMaster = $this->baseApi->callApiArray(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $leagueGrandMaster ?
            $this->denormalizerApi->denormalize($leagueGrandMaster, LeagueListDTO::class) : null;
    }

    /**
     * @throws ForbiddenException
     * @throws LeagueArgumentException
     */
    public function leagueMasterByQueue(
        string $queue
    ): ?LeagueListDTO {
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

        $leagueMaster = $this->baseApi->callApiArray(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $leagueMaster ?
            $this->denormalizerApi->denormalize($leagueMaster, LeagueListDTO::class) : null;
    }

    /**
     * @throws ForbiddenException
     */
    public function leagueByLeagueId(
        string $leagueId
    ): ?LeagueListDTO {
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

        $league = $this->baseApi->callApiArray(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $league ?
            $this->denormalizerApi->denormalize($league, LeagueListDTO::class) : null;
    }

    /**
     * @throws ForbiddenException
     * @throws LeagueArgumentException
     *
     * @return LeagueEntryDTO[]|null
     */
    public function leagueByQueueByTierByDivision(
        string $queue,
        string $tier,
        string $division
    ): ?array {
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

        $league = $this->baseApi->callApiArray(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $league ?
            $this->denormalizerApi->denormalizeArray($league, LeagueEntryDTO::class) : null;
    }
}
