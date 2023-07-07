<?php

declare(strict_types=1);

namespace App\Services\API\LOL\DataDragon;

use App\Services\API\LOL\BaseApi;
use Symfony\Component\HttpFoundation\Request;

class GeneralApi
{
    public function __construct(
        private readonly BaseApi $baseApi
    ) {
    }

    public const URL_SEASON = 'https://static.developer.riotgames.com/docs/lol/seasons.json';
    public const URL_MAPS = 'https://static.developer.riotgames.com/docs/lol/maps.json';
    public const URL_GAMEMODES = 'https://static.developer.riotgames.com/docs/lol/gameModes.json';
    public const URL_GAMETYPES = 'https://static.developer.riotgames.com/docs/lol/gameTypes.json';

    public function getSeason(): ?array
    {
        return $this->baseApi->callApi(self::URL_SEASON, Request::METHOD_GET);
    }

    public function getMaps(): ?array
    {
        return $this->baseApi->callApi(self::URL_MAPS, Request::METHOD_GET);
    }

    public function getGameModes(): ?array
    {
        return $this->baseApi->callApi(self::URL_GAMEMODES, Request::METHOD_GET);
    }

    public function getGameTypes(): ?array
    {
        return $this->baseApi->callApi(self::URL_GAMETYPES, Request::METHOD_GET);
    }
}
