<?php

namespace App\Services\API\LOL\DataDragon;

use App\Services\API\LOL\BaseApi;
use Symfony\Component\HttpFoundation\Request;

class DataDragonApi
{
    public const URL_RACINE = 'https://ddragon.leagueoflegends.com/';
    public const URL_VERSION = self::URL_RACINE . 'api/versions.json';
    public const URL_LANGUAGE = self::URL_RACINE . 'cdn/languages.json';
    public const URL_CHAMPIONS = self::URL_RACINE . 'cdn/{version}/data/{lang}/champion.json';
    public const URL_CHAMPION = self::URL_RACINE . 'cdn/{version}/data/{lang}/champion/{champion}.json';
    public const URL_ITEMS = self::URL_RACINE . 'cdn/{version}/data/{lang}/item.json';
    public const URL_SUMMONER = self::URL_RACINE . 'cdn/{version}/data/{lang}/summoner.json';

    public function __construct(
        private BaseApi $baseApi,
    ) {
    }

    /**
     * @return array|null
     */
    public function getVersions()
    {
        return $this->baseApi->callApi(self::URL_VERSION, Request::METHOD_GET);
    }

    public function getLanguages()
    {
        return $this->baseApi->callApi(self::URL_LANGUAGE, Request::METHOD_GET);
    }

    /**
     * @return array|null
     */
    public function getChampions(string $version = null, string $lang = 'fr_FR')
    {
        if (null === $version) {
            $version = $this->getLastVersion();
        }

        $url = $this->baseApi->constructUrl(
            self::URL_CHAMPIONS,
            [
                'version' => $version,
                'lang' => $lang,
            ]
        );

        return $this->baseApi->callApi($url, Request::METHOD_GET);
    }

    /**
     * @return array|null
     */
    public function getChampion(string $champion, string $version = null, string $lang = 'fr_FR')
    {
        if (null === $version) {
            $version = $this->getLastVersion();
        }

        $url = $this->baseApi->constructUrl(
            self::URL_CHAMPION,
            [
                'champion' => ucfirst($champion),
                'version' => $version,
                'lang' => $lang,
            ]
        );

        return $this->baseApi->callApi($url, Request::METHOD_GET);
    }

    /**
     * @return bool[]|int[]|string|string[]|null
     */
    public function getItems(string $version = null, string $lang = 'fr_FR')
    {
        if (null === $version) {
            $version = $this->getLastVersion();
        }

        $url = $this->baseApi->constructUrl(
            self::URL_ITEMS,
            [
                'version' => $version,
                'lang' => $lang,
            ]
        );

        return $this->baseApi->callApi($url, Request::METHOD_GET);
    }

    public function getSummoner(string $version = null, string $lang = 'fr_FR')
    {
        if (null === $version) {
            $version = $this->getLastVersion();
        }

        $url = $this->baseApi->constructUrl(
            self::URL_SUMMONER,
            [
                'version' => $version,
                'lang' => $lang,
            ]
        );
    }

    /**
     * @return mixed
     */
    private function getLastVersion()
    {
        return $this->getVersions()[0];
    }
}
