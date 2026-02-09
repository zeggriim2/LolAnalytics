<?php

declare(strict_types=1);

namespace App\Summoner\Infrastructure\Adapter;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\SharedContext\Domain\ValueObjet\Region;
use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Port\RiotSummonerProviderInterface;
use Zeggriim\RiotApiDataDragon\DataLeague\Endpoint\AccountApiInterface;
use Zeggriim\RiotApiDataDragon\DataLeague\Endpoint\SummonerApiInterface;
use Zeggriim\RiotApiDataDragon\Enum\Platform as RiotPlatform;
use Zeggriim\RiotApiDataDragon\Enum\Region as RiotRegion;

final readonly class RiotSummonerProvider implements RiotSummonerProviderInterface
{
    public function __construct(
        private SummonerApiInterface $summonerApi,
        private AccountApiInterface $accountApi,
    ) {
    }

    public function fetchByPuuid(string $puuid, Platform $platform, Region $region): SummonerDto
    {
        $riotPlatform = RiotPlatform::from($platform->value);
        $riotRegion = RiotRegion::from($region->value);

        $accountData = $this->accountApi->getAccountByPuuid($puuid, $riotRegion);
        $summonerData = $this->summonerApi->getSummoner($puuid, $riotPlatform);

        return new SummonerDto(
            puuid: $puuid,
            gameName: $accountData['gameName'],
            tagLine: $accountData['tagLine'],
            profileIconId: $summonerData['profileIconId'],
            summonerLevel: $summonerData['summonerLevel'],
            platform: $platform->value,
            lastUpdatedAt: $this->convertRevisionDate($summonerData['revisionDate']),
        );
    }

    public function fetchByRiotId(string $gameName, string $tagLine, Platform $platform, Region $region): SummonerDto
    {
        $riotPlatform = RiotPlatform::from($platform->value);
        $riotRegion = RiotRegion::from($region->value);

        $accountData = $this->accountApi->getAccountByRiotId($gameName, $tagLine, $riotRegion);
        $puuid = $accountData['puuid'];

        $summonerData = $this->summonerApi->getSummoner($puuid, $riotPlatform);

        return new SummonerDto(
            puuid: $puuid,
            gameName: $accountData['gameName'],
            tagLine: $accountData['tagLine'],
            profileIconId: $summonerData['profileIconId'],
            summonerLevel: $summonerData['summonerLevel'],
            platform: $platform->value,
            lastUpdatedAt: $this->convertRevisionDate($summonerData['revisionDate']),
        );
    }

    private function convertRevisionDate(int $revisionDate): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp((int) ($revisionDate / 1000));
    }
}
