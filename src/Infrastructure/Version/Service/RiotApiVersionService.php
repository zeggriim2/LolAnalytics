<?php

declare(strict_types=1);

namespace App\Infrastructure\Version\Service;

use App\Domain\Version\Model\Version;
use App\Domain\Version\Service\VersionServiceInterface;
use DateTimeImmutable;
use Zeggriim\RiotApiDataDragon\DataDragon\DataDragonApiInterface;

final class RiotApiVersionService implements VersionServiceInterface
{
    public function __construct(
        private readonly DataDragonApiInterface $dataDragonClient
    ) {
    }

    public function fetchVersionsFromApi(): array
    {
        $versionsData = $this->dataDragonClient->versions()->getVersions();
        $createdAt = new DateTimeImmutable();

        return array_map(
            fn(string $version) => new Version($version, $createdAt),
            $versionsData
        );
    }
}
