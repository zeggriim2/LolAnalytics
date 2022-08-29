<?php

declare(strict_types=1);

namespace App\Services\API\LOL;

use App\Services\API\LOL\DataDragon\Platform;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BaseApi
{
    public const URL_RACINE_REGION = 'https://{region}.api.riotgames.com/lol/';
    public const URL_RACINE_PLATFORM = 'https://{platform}.api.riotgames.com/lol/';

    public string $apiKey;
    public string $platform;
    public HttpClientInterface $client;

    public function __construct(
        string $apiKey,
        string $platform,
        HttpClientInterface $client,
    ) {
        $this->apiKey = $apiKey;
        $this->client = $client;
//        $session->has('platform')
//            ? $this->platform = $session->get('platform')
//            : $this->platform = $platform;
        $this->platform = $platform;
    }

    /**
     * @param array<string,string> $params
     */
    public function constructUrl(string $url, array $params): string
    {
        foreach ($params as $key => $param) {
            $url = str_replace("{{$key}}", $param, $url);
        }

        return $url;
    }

    /**
     * @param array<string,string|array<string|string>> $options
     *
     * @return mixed[]|string|null
     */
    public function callApi(string $url, string $method = 'GET', array $options = [], string $return = 'array')
    {
        $response = $this->client->request($method, $url, $options);
        $statusCode = $response->getStatusCode();
        switch ($statusCode) {
            case Response::HTTP_OK:
                if ('array' === $return) {
                    return $response->toArray();
                }

                return $response->getContent();

            case Response::HTTP_NOT_FOUND:
            default:
                return null;
        }
    }

    /**
     * @param string $platform
     * @return void
     */
    public function changePlatrform(string $platform)
    {
        if (\in_array($platform, Platform::getChoices(), true)) {
            $this->platform = $platform;
        }
    }
}
