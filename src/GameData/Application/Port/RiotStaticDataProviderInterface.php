<?php

declare(strict_types=1);

namespace App\GameData\Application\Port;

use App\GameData\Application\Dto\GameModeDto;
use App\GameData\Application\Dto\GameTypeDto;
use App\GameData\Application\Dto\MapDto;
use App\GameData\Application\Dto\QueueDto;
use App\GameData\Application\Dto\SeasonDto;

interface RiotStaticDataProviderInterface
{
    /**
     * @return QueueDto[]
     */
    public function fetchQueues(): array;

    /**
     * @return GameModeDto[]
     */
    public function fetchGameModes(): array;

    /**
     * @return GameTypeDto[]
     */
    public function fetchGameTypes(): array;

    /**
     * @return MapDto[]
     */
    public function fetchMaps(): array;

    /**
     * @return SeasonDto[]
     */
    public function fetchSeasons(): array;
}
