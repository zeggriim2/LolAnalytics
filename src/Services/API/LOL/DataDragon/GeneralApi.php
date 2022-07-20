<?php

namespace App\Services\API\LOL\DataDragon;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeneralApi
{
    public const URL_SEASON = 'https://static.developer.riotgames.com/docs/lol/seasons.json';
    public const URL_MAPS = 'https://static.developer.riotgames.com/docs/lol/maps.json';
    public const URL_GAMEMODES = 'https://static.developer.riotgames.com/docs/lol/gameModes.json';
    public const URL_GAMETYPES = 'https://static.developer.riotgames.com/docs/lol/gameTypes.json';

    public function __construct(
        private HttpClientInterface $httpClient
    ) {
    }

    public function getSeason()
    {
        return $this->callApi(Request::METHOD_GET, self::URL_SEASON);
    }

    public function getMaps()
    {
        return $this->callApi(Request::METHOD_GET, self::URL_MAPS);
    }

    public function getGameModes()
    {
        return $this->callApi(Request::METHOD_GET, self::URL_GAMEMODES);
    }

    public function getGameTypes()
    {
        return $this->callApi(Request::METHOD_GET, self::URL_GAMETYPES);
    }

    /**
     * @return array|null
     */
    private function callApi(string $method, string $url)
    {
        $response = $this->httpClient->request($method, $url);
        if (200 === $response->getStatusCode()) {
            return $response->toArray();
        }

        return null;
    }
}
