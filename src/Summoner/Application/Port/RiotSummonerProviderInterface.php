<?php

declare(strict_types=1);

namespace App\Summoner\Application\Port;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\SharedContext\Domain\ValueObjet\Region;
use App\Summoner\Application\Dto\SummonerDto;

interface RiotSummonerProviderInterface
{
    public function fetchByPuuid(string $puuid, Platform $platform, Region $region): SummonerDto;

    public function fetchByRiotId(string $gameName, string $tagLine, Platform $platform, Region $region): SummonerDto;
}
