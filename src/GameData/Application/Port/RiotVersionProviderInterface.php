<?php

declare(strict_types=1);

namespace App\GameData\Application\Port;

use App\GameData\Application\Dto\VersionDto;

interface RiotVersionProviderInterface
{
    /**
     * @return VersionDto[]
     */
    public function fetchVersions(): array;
}
