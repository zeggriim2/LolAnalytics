<?php

declare(strict_types=1);

namespace App\Services\API\LOL\DataDragon;

use App\Services\API\LOL\BaseApi;
use App\Services\API\LOL\Helper\UrlHelper;
use Symfony\Component\HttpFoundation\Request;

class DataDragonApi
{
    private const URL_RACINE = 'https://ddragon.leagueoflegends.com/';
    public const URL_VERSION = self::URL_RACINE . 'api/versions.json';
    public const URL_LANGUAGE = self::URL_RACINE . 'cdn/languages.json';
    public const URL_CHAMPIONS = self::URL_RACINE . 'cdn/{version}/data/{lang}/champion.json';
    public const URL_CHAMPION = self::URL_RACINE . 'cdn/{version}/data/{lang}/champion/{champion}.json';
    public const URL_ITEMS = self::URL_RACINE . 'cdn/{version}/data/{lang}/item.json';
    public const URL_SUMMONER = self::URL_RACINE . 'cdn/{version}/data/{lang}/summoner.json';

    public function __construct(
        private readonly BaseApi $baseApi,
    ) {
    }

    public function getVersions(): ?array
    {
        return $this->baseApi->callApi(self::URL_VERSION, Request::METHOD_GET);
    }

    public function getLanguages(): ?array
    {
        return $this->baseApi->callApi(self::URL_LANGUAGE, Request::METHOD_GET);
    }

    public function getChampions(string $version = null, string $lang = 'fr_FR'): ?array
    {
        if (null === $version) {
            $version = $this->getLastVersion();
        }

        $url = UrlHelper::constructUrl(self::URL_CHAMPIONS,
            [
                'version' => $version,
                'lang' => $lang,
            ]
        );

        return $this->baseApi->callApi($url, Request::METHOD_GET);
    }

    public function getChampion(string $champion, string $version = null, string $lang = 'fr_FR'): ?array
    {
        if (null === $version) {
            $version = $this->getLastVersion();
        }

        $url = UrlHelper::constructUrl(self::URL_CHAMPION,
            [
                'champion' => ucfirst($champion),
                'version' => $version,
                'lang' => $lang,
            ]
        );

        return $this->baseApi->callApi($url, Request::METHOD_GET);
    }

    public function getItems(string $version = null, string $lang = 'fr_FR'): ?array
    {
        if (null === $version) {
            $version = $this->getLastVersion();
        }
        $url = UrlHelper::constructUrl(self::URL_ITEMS,
            [
                'version' => $version,
                'lang' => $lang,
            ]
        );

        return $this->baseApi->callApi($url, Request::METHOD_GET);
    }

    public function getSummoner(string $version = null, string $lang = 'fr_FR'): ?array
    {
        if (null === $version) {
            $version = $this->getLastVersion();
        }

        $url = UrlHelper::constructUrl(self::URL_SUMMONER,
            [
                'version' => $version,
                'lang' => $lang,
            ]
        );

        return $this->baseApi->callApi($url, Request::METHOD_GET);
    }

    public function getLastVersion(): ?string
    {
        $versions = $this->getVersions();

        return $versions ? $versions[0] : null;
    }
}
