<?php

declare(strict_types=1);

namespace App\Match\Infrastructure\Client;

use App\Match\Infrastructure\Client\Enum\Type;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class RiotApiClient implements RiotApiClientInterface
{
    const URL_MATCH = 'https://%s.api.riotgames.com/lol/match/v5/matches/%s';
    const URL_MATCH_BY_PUUID = 'https://%s.api.riotgames.com/lol/match/v5/matches/by-puuid/%s/ids?type=%s&start=%d&count=%d';

    public function __construct(
        private readonly HttpClientInterface $riotApiDataleague
    ) {}

    public function fetchMatch(string $matchId, string $region): array
    {
        $url = sprintf(self::URL_MATCH ,$region, $matchId);

        try {
            $response = $this->riotApiDataleague->request('GET', $url);
            $response->getContent();
        } catch (ExceptionInterface $exception) {
            throw $exception;
        }

        return $response->toArray();
    }

    public function fetchMatchesByPuuid(
        string $puuid,
        string $region,
        Type $type = Type::RANKED,
        int $start = 0,
        int $count = 20
    ): array {
        $url = sprintf(self::URL_MATCH_BY_PUUID, $region, $puuid, $type->value, $start, $count);

        try {
            $response = $this->riotApiDataleague->request('GET', $url);
            $response->getContent();
        } catch (ExceptionInterface $exception) {
            throw $exception;
        }

        return $response->toArray();
    }
}
