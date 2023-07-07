<?php

declare(strict_types=1);

namespace App\Services\API\LOL\LeagueOfLegends;

use App\Services\API\LOL\BaseApi;
use App\Services\API\LOL\Helper\UrlHelper;
use App\Services\API\LOL\LeagueOfLegends\DTO\Status\PlatformDataDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class StatusApi
{
    private const URL_STATUS = BaseApi::URL_RACINE_PLATFORM . 'status/v4/platform-data';

    public function __construct(
        private readonly BaseApi $baseApi,
        private readonly DenormalizerInterface $denormalizer
    ) {
    }

    public function status(): ?PlatformDataDto
    {
        $url = UrlHelper::constructUrl(self::URL_STATUS, ['platform' => $this->baseApi->platform]);

        $status = $this->baseApi->callApi(
            $url,
            Request::METHOD_GET,
            [
                'headers' => ['X-Riot-Token' => $this->baseApi->apiKey],
            ]
        );

        return $status ? $this->denormalize($status) : null;
    }

    /**
     * @param array<string,int|string> $data
     *
     * @return PlatformDataDto
     */
    private function denormalize(array $data)
    {
        return $this->denormalizer->denormalize($data, PlatformDataDto::class);
    }
}
