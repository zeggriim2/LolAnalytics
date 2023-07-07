<?php

declare(strict_types=1);

namespace App\Services\API\LOL\LeagueOfLegends;

use App\Services\API\LOL\BaseApi;
use App\Services\API\LOL\Helper\UrlHelper;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\MatchDto;
use App\Services\API\LOL\LeagueOfLegends\Exception\ForbiddenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class MatchApi
{
    private const URL_MATCH_PUUID =
        BaseApi::URL_RACINE_REGION . 'match/v5/matches/by-puuid/{puuid}/ids?start={start}&count={count}';
    private const URL_MATCH_ID =
        BaseApi::URL_RACINE_REGION . 'match/v5/matches/{matchId}';
    private const URL_MATCH_TIMELINE_MATCH_ID =
        BaseApi::URL_RACINE_REGION . 'match/v5/matches/{matchId}/timeline';

    public function __construct(
        private readonly BaseApi $baseApi,
        private readonly DenormalizerInterface $denormalizer
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
        int $count = 20
    ): ?array {
        if ('' === $puuid) {
            throw new ForbiddenException('Puuid est vide');
        }

        $url = UrlHelper::constructUrl(self::URL_MATCH_PUUID,
            [
                'region' => 'europe',
                'puuid' => $puuid,
                'start' => (string) $start,
                'count' => (string) $count,
            ]
        );

        return $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => ['X-Riot-Token' => $this->baseApi->apiKey],
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

        $url = UrlHelper::constructUrl(self::URL_MATCH_ID,
            [
                'region' => 'europe',
                'matchId' => $matchId,
            ]
        );

        $matchDetailId = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => ['X-Riot-Token' => $this->baseApi->apiKey],
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

        $url = UrlHelper::constructUrl(self::URL_MATCH_TIMELINE_MATCH_ID,
            [
                'region' => 'europe',
                'matchId' => $matchId,
            ]
        );

        $matchTimeLine = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => ['X-Riot-Token' => $this->baseApi->apiKey],
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
}
