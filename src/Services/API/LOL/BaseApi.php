<?php

declare(strict_types=1);

namespace App\Services\API\LOL;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BaseApi
{
    public const URL_RACINE_REGION = 'https://{region}.api.riotgames.com/lol/';
    public const URL_RACINE_PLATFORM = 'https://{platform}.api.riotgames.com/lol/';

    public function __construct(
        public readonly string $apiKey,
        public readonly string $platform,
        public readonly HttpClientInterface $client
    ) {
    }

    /**
     * @param array<string,string|array<string|string>> $options
     *
     * @return string|array<int|string|array-key,int|string|bool>|null
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
            default:
                return null;
        }
    }
}
