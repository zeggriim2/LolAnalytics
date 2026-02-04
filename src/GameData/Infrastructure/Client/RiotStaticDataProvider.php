<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Client;

use App\GameData\Application\Dto\GameModeDto;
use App\GameData\Application\Dto\GameTypeDto;
use App\GameData\Application\Dto\MapDto;
use App\GameData\Application\Dto\QueueDto;
use App\GameData\Application\Dto\SeasonDto;
use App\GameData\Application\Port\RiotStaticDataProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class RiotStaticDataProvider implements RiotStaticDataProviderInterface
{
    private const BASE_URL = 'https://static.developer.riotgames.com/docs/lol';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {
    }

    /**
     * @return QueueDto[]
     */
    public function fetchQueues(): array
    {
        $data = $this->fetch('/queues.json');

        return array_map(
            static fn (array $item): QueueDto => QueueDto::fromArray($item),
            $data
        );
    }

    /**
     * @return GameModeDto[]
     */
    public function fetchGameModes(): array
    {
        $data = $this->fetch('/gameModes.json');

        return array_map(
            static fn (array $item): GameModeDto => GameModeDto::fromArray($item),
            $data
        );
    }

    /**
     * @return GameTypeDto[]
     */
    public function fetchGameTypes(): array
    {
        $data = $this->fetch('/gameTypes.json');

        return array_map(
            static fn (array $item): GameTypeDto => GameTypeDto::fromArray($item),
            $data
        );
    }

    /**
     * @return MapDto[]
     */
    public function fetchMaps(): array
    {
        $data = $this->fetch('/maps.json');

        return array_map(
            static fn (array $item): MapDto => MapDto::fromArray($item),
            $data
        );
    }

    /**
     * @return SeasonDto[]
     */
    public function fetchSeasons(): array
    {
        $data = $this->fetch('/seasons.json');

        return array_map(
            static fn (array $item): SeasonDto => SeasonDto::fromArray($item),
            $data
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetch(string $endpoint): array
    {
        $response = $this->httpClient->request(Request::METHOD_GET, self::BASE_URL . $endpoint);

        return $response->toArray();
    }
}
