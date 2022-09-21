<?php

namespace App\Services\API\LOL\LeagueOfLegends;

use App\Services\API\LOL\BaseApi;
use App\Services\API\LOL\LeagueOfLegends\Denormalize\DenormalizerApi;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\MatchDto;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use Symfony\Component\HttpFoundation\Request;

class MatchApi
{
    private const URL_MATCH_PUUID =
//        BaseApi::URL_RACINE_REGION . 'match/v5/matches/by-puuid/{puuid}/ids?start={start}&count={count}';
        BaseApi::URL_RACINE_REGION . 'match/v5/matches/by-puuid/{puuid}/ids';
    private const URL_MATCH_ID =
        BaseApi::URL_RACINE_REGION . 'match/v5/matches/{matchId}';
    private const URL_MATCH_TIMELINE_MATCH_ID =
        BaseApi::URL_RACINE_REGION . 'match/v5/matches/{matchId}/timeline';

    public const TYPE_RANKED = 'ranked';
    public const TYPE_NORMAL = 'normal';
    public const TYPE_TOURNEY = 'tourney';
    public const TYPE_TUTORIAL = 'tutorial';

    private const TYPE = [
        self::TYPE_RANKED,
        self::TYPE_NORMAL,
        self::TYPE_TOURNEY,
        self::TYPE_TUTORIAL,
    ];

    public function __construct(
        private BaseApi $baseApi,
        private DenormalizerApi $denormalizerApi
    ) {
    }

    /**
     * @throws ForbiddenException
     *
     * @return mixed[]|null
     */
    public function getMatchByPuuid(
        string $puuid,
        int $start = 0,
        int $count = 20,
        ?string $type = null
    ): ?array {
        if ('' === $puuid) {
            throw new ForbiddenException('Puuid est vide');
        }

        $url = $this->baseApi->constructUrl(
            self::URL_MATCH_PUUID,
            [
                'region' => 'europe',
                'puuid' => $puuid,
            ]
        );

        $query = $this->constructQuery([
            'start' => $start,
            'count' => $count,
            'type' => $type,
        ]);

        return $this->baseApi->callApiArray(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
                'query' => $query,
            ]
        );
    }

    public function getMatchById(
        string $matchId
    ): ?MatchDto {
        if ('' === $matchId) {
            throw new ForbiddenException('Match ID est vide');
        }

        $url = $this->baseApi->constructUrl(
            self::URL_MATCH_ID,
            [
                'region' => 'europe',
                'matchId' => $matchId,
            ]
        );

        $matchDetailId = $this->baseApi->callApiArray(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $matchDetailId ?
            $this->denormalizerApi->denormalize($matchDetailId, MatchDto::class) :
            null;
    }

    /**
     * @throws ForbiddenException
     *
     * @return mixed[]|null
     */
    public function getMatchTimeLineByMatchId(
        string $matchId
    ): array|null {
        if ('' === $matchId) {
            throw new ForbiddenException('Match ID est vide');
        }

        $url = $this->baseApi->constructUrl(
            self::URL_MATCH_TIMELINE_MATCH_ID,
            [
                'region' => 'europe',
                'matchId' => $matchId,
            ]
        );

        return $this->baseApi->callApiArray(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );
    }

    /**
     * @param array<string, string|int|null> $params
     *
     * @return array<string, string|int>
     */
    private function constructQuery(array $params): array
    {
        $query = [];
        foreach ($params as $key => $param) {
            if (null !== $param) {
                if ('type' === $key && !\in_array($param, self::TYPE, true)) {
                    continue;
                }
                $query[$key] = $param;
            }
        }

        return $query;
    }
}
