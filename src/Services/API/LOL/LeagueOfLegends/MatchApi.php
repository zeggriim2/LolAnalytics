<?php

namespace App\Services\API\LOL\LeagueOfLegends;

use App\Services\API\LOL\BaseApi;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\MatchDto;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

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
        private DenormalizerInterface $denormalizer
    ) {
    }

    /**
     * @throws ForbiddenException
     *
     * @return string[]|null
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
                'puuid' => $puuid
            ]
        );

        $query = $this->constructQuery([
            'start' => $start,
            'count' => $count,
            'type' => $type
        ]);

        return $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
                'query' => $query
            ]
        );
    }

    /**
     * @throws ForbiddenException
     */
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

        $matchDetailId = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $matchDetailId ? $this->denormalize($matchDetailId) : null;
    }

    public function getMatchTimeLineByMatchId(
        string $matchId
    ) {
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

        $matchTimeLine = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => [
                    'X-Riot-Token' => $this->baseApi->apiKey,
                ],
            ]
        );

        return $matchTimeLine;
    }

    /**
     * @return MatchDto
     */
    private function denormalize(array $data)
    {
        return $this->denormalizer->denormalize($data, MatchDto::class);
    }

    private function constructQuery(array $params)
    {
        $query = [];
        foreach($params as $key => $param) {
            if($param !== null) {
                if($key === 'type' && !in_array($param, self::TYPE)) {
                    continue;
                }
                $query[$key] = $param;
            }
        }
        return $query;
    }
}
