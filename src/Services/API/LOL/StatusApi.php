<?php

namespace App\Services\API\LOL;

use App\Services\API\LOL\DTO\PlatformDataDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class StatusApi
{
    private const URL_STATUS = BaseApi::URL_RACINE_PLATFORM . "status/v4/platform-data";

    private BaseApi $baseApi;
    private DenormalizerInterface $denormalizer;

    public function __construct(
        BaseApi $baseApi,
        DenormalizerInterface $denormalizer
    ) {
        $this->baseApi = $baseApi;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @return PlatformDataDto|null
     */
    public function status()
    {
        $url = $this->baseApi->constructUrl(
            self::URL_STATUS,
            [
                "platform" => $this->baseApi->platform
            ]
        );

        /** @var array<string,int|string>|null $status */
        $status = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                "headers" => [
                    "X-Riot-Token" => $this->baseApi->apiKey
                ]
            ]
        );

        if (is_null($status)) {
            return null;
        }

        return $this->denormalize($status);
    }

    /**
     * @param array<string,int|string> $data
     * @return PlatformDataDto
     */
    private function denormalize(array $data)
    {
        return $this->denormalizer->denormalize($data, PlatformDataDto::class);
    }
}
