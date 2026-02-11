<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Client;

use App\GameData\Application\Dto\VersionDto;
use App\GameData\Application\Port\RiotVersionProviderInterface;
use Zeggriim\RiotApiDataDragon\DataDragon\Endpoint\VersionApiInterface;

final class RiotVersionProvider implements RiotVersionProviderInterface
{
    public function __construct(
        private readonly VersionApiInterface $versionApi,
    ) {
    }

    /**
     * @return VersionDto[]
     */
    public function fetchVersions(): array
    {
        $versionData = $this->versionApi->getVersions();

        return array_map(
            static fn (string $item): VersionDto => VersionDto::fromString($item),
            $versionData
        );
    }
}
